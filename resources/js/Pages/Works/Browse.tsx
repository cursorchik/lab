import React, { useState } from 'react';
import { Link, router } from '@inertiajs/react';
import TableBrowse from '@/Components/custom/Table';
import Modal from '@/Components/custom/modal/modal';
import styles from '@/Components/custom/modal/styles.module.scss';
import { STATES } from '@/Pages/Works/Types';

type OptionType = {
	value: string;
	label: string;
};

type Filters = {
	date: string;
	patient: string;
	mechanics: {
		selected: number;
		items: OptionType[];
	};
	clinics: {
		selected: number;
		items: { id: number; name: string }[];
	};
	lock_type: string;
};

type WorkItem = {
	id: number;
	start: string;
	end: string | null;
	state: number;
	patient: string;
	clinic: { id: number; name: string } | null;
	mechanic: { id: number; name: string } | null;
	work_types: {
		id: number;
		name: string;
		cost: number;
		pivot: { work_id: number; work_type_id: number; count: number };
	}[];
	comment: string;
	locked_by_clinic: boolean;
	locked_by_mechanic: boolean;
	locked_both: boolean;
};

type WorksProps = {
	prev_url: string;
	items: WorkItem[];
	default_filters: Filters;
	filters: Filters;
	sort?: string;
	direction?: string;
};

export default function Browse(props: WorksProps)
{
	const [filterModal, setFilterModal] = useState(false);
	const [unlockModal, setUnlockModal] = useState<{
		workId: number;
		clinicLocked: boolean;
		mechanicLocked: boolean;
	} | null>(null);
	const [unlockType, setUnlockType] = useState<'clinic' | 'mechanic' | 'both'>('both');

	const { sort = 'id', direction = 'asc' } = props;

	// Ссылка для сортировки
	const getSortLink = (field: string) =>
	{
		const params = new URLSearchParams(window.location.search);
		const currentSort = params.get('sort');
		const currentDirection = params.get('direction');

		let newDirection = 'asc';
		if (currentSort === field) newDirection = currentDirection === 'asc' ? 'desc' : 'asc';

		params.set('sort', field);
		params.set('direction', newDirection);

		return `${window.location.pathname}?${params.toString()}`;
	};

	// Отображение статуса блокировки
	const getLockStatus = (work: WorkItem) =>
	{
		if (work.locked_both) {
			return <span className="badge bg-danger">Заблокирована (кл+тех)</span>;
		}
		if (work.locked_by_clinic) {
			return <span className="badge bg-warning">Заблокирована клиникой</span>;
		}
		if (work.locked_by_mechanic) {
			return <span className="badge bg-info">Заблокирована техником</span>;
		}
		return <span className="badge bg-success">Не заблокирована</span>;
	};

	// Открытие модалки снятия блокировки
	const openUnlockModal = (workId: number, clinicLocked: boolean, mechanicLocked: boolean) =>
	{
		setUnlockModal({ workId, clinicLocked, mechanicLocked });
		if (clinicLocked && !mechanicLocked) setUnlockType('clinic');
		else if (!clinicLocked && mechanicLocked) setUnlockType('mechanic');
		else if (clinicLocked && mechanicLocked) setUnlockType('both');
	};

	const closeUnlockModal = () => setUnlockModal(null);

	// Отправка запроса на снятие блокировки
	const handleUnlock = () =>
	{
		if (!unlockModal) return;
		router.post(`/works/unlock/${unlockModal.workId}`, { unlock_type: unlockType }, {
			preserveScroll: true,
			onSuccess: () => closeUnlockModal(),
		});
	};

	// Остановка всплытия кликов внутри модалки
	const handleModalClick = (e: React.MouseEvent) => e.stopPropagation();

	// Применение фильтров
	const applyFilters = () =>
	{
		const params = new URLSearchParams();

		const date = (document.getElementById('date') as HTMLInputElement)?.value;
		const patient = (document.getElementById('patient') as HTMLTextAreaElement)?.value;
		const mid = (document.getElementById('mid') as HTMLSelectElement)?.value;
		const cid = (document.getElementById('cid') as HTMLSelectElement)?.value;
		const lockType = (document.getElementById('lock_type') as HTMLSelectElement)?.value;

		if (date) params.append('date', date);
		if (patient) params.append('patient', patient);
		if (mid && mid !== '0') params.append('mid', mid);
		if (cid && cid !== '0') params.append('cid', cid);
		if (lockType && lockType !== '') params.append('lock_type', lockType);

		// Сохраняем текущую сортировку
		if (sort) params.append('sort', sort);
		if (direction) params.append('direction', direction);

		// Выполняем GET-запрос с параметрами
		router.get(`/?${params.toString()}`, {}, {
			preserveState: true,
			preserveScroll: true,
			onSuccess: () => setFilterModal(false),
		});
	};

	return (
		<TableBrowse urls={{ add: '/works/create' }} title="Список работ">
			<a className="btn btn-link" onClick={() => setFilterModal(true)}>Фильтры</a>

			{/* Модалка фильтров */}
			{filterModal && (
				<Modal close={() => setFilterModal(false)}>
					<div className={styles.filters} onClick={handleModalClick}>
						<h3>Фильтры</h3>
						<div className={styles.filters_content}>
							<div>
								<div className="mb-3">
									<label htmlFor="date" className="form-label">Дата</label>
									<input
										type="date"
										className="form-control"
										id="date"
										name="date"
										defaultValue={props.filters.date ?? ''}
									/>
								</div>
							</div>
							<div>
								<div className="mb-3">
									<label htmlFor="patient" className="form-label">Пациент</label>
									<textarea
										name="patient"
										className="form-control"
										id="patient"
										defaultValue={props.filters.patient ?? ''}
									/>
								</div>
							</div>
							<div>
								<div className="mb-3">
									<label htmlFor="mid" className="form-label">Техник</label>
									<select
										className="form-select"
										id="mid"
										name="mid"
										defaultValue={props.filters.mechanics.selected}
									>
										<option value="0">Не выбрано</option>
										{props.filters.mechanics.items.map((item) => (
											<option key={item.value} value={item.value}>
												{item.label}
											</option>
										))}
									</select>
								</div>
							</div>
							<div>
								<div className="mb-3">
									<label htmlFor="cid" className="form-label">Клиника</label>
									<select
										className="form-select"
										id="cid"
										name="cid"
										defaultValue={props.filters.clinics.selected}
									>
										<option value="0">Не выбрано</option>
										{props.filters.clinics.items.map((item) => (
											<option key={item.id} value={item.id}>
												{item.name}
											</option>
										))}
									</select>
								</div>
							</div>
							<div>
								<div className="mb-3">
									<label htmlFor="lock_type" className="form-label">Тип блокировки</label>
									<select
										className="form-select"
										id="lock_type"
										name="lock_type"
										defaultValue={props.filters.lock_type ?? ''}
									>
										<option value="">Все работы</option>
										<option value="none">Не заблокированы</option>
										<option value="clinic">Заблокированы клиникой</option>
										<option value="mechanic">Заблокированы техником</option>
										<option value="both">Заблокированы клиникой и техником</option>
										<option value="any">Заблокированы любой стороной</option>
									</select>
								</div>
							</div>
						</div>
						<div className="flex gap-1.5 justify-end">
							<Link
								className="btn btn-outline-dark"
								href="/"
								method="get"
								only={[]}
								onClick={() => setFilterModal(false)}
							>
								Сбросить
							</Link>
							<button className="btn btn-primary" onClick={applyFilters}>
								Применить
							</button>
						</div>
					</div>
				</Modal>
			)}

			{/* Модалка снятия блокировки */}
			{unlockModal && (
				<Modal close={closeUnlockModal}>
					<div className={styles.filters} onClick={handleModalClick}>
						<h3>Снять блокировку</h3>
						<div className="mb-3">
							<label>Выберите тип блокировки для снятия:</label>
							<div className="mt-2">
								{unlockModal.clinicLocked && (
									<div className="form-check">
										<input
											type="radio"
											className="form-check-input"
											id="unlock_clinic"
											value="clinic"
											checked={unlockType === 'clinic'}
											onChange={() => setUnlockType('clinic')}
										/>
										<label className="form-check-label" htmlFor="unlock_clinic">
											Снять блокировку клиникой
										</label>
									</div>
								)}
								{unlockModal.mechanicLocked && (
									<div className="form-check">
										<input
											type="radio"
											className="form-check-input"
											id="unlock_mechanic"
											value="mechanic"
											checked={unlockType === 'mechanic'}
											onChange={() => setUnlockType('mechanic')}
										/>
										<label className="form-check-label" htmlFor="unlock_mechanic">
											Снять блокировку техником
										</label>
									</div>
								)}
								{(unlockModal.clinicLocked && unlockModal.mechanicLocked) && (
									<div className="form-check">
										<input
											type="radio"
											className="form-check-input"
											id="unlock_both"
											value="both"
											checked={unlockType === 'both'}
											onChange={() => setUnlockType('both')}
										/>
										<label className="form-check-label" htmlFor="unlock_both">
											Снять обе блокировки
										</label>
									</div>
								)}
							</div>
						</div>
						<div className="flex gap-1.5 justify-end">
							<button className="btn btn-outline-dark" onClick={closeUnlockModal}>
								Отмена
							</button>
							<button className="btn btn-primary" onClick={handleUnlock}>
								Снять
							</button>
						</div>
					</div>
				</Modal>
			)}

			<table className="table text-sm">
				<thead>
				<tr>
					<th className="col">
						<Link
							title={direction === 'asc' ? 'Сортировка по возрастанию' : 'Сортировка по убыванию'}
							href={getSortLink('id')}
							preserveState
							className="whitespace-nowrap text-decoration-none"
						>
							ID {sort === 'id' && (direction === 'asc' ? '▲' : '▼')}
						</Link>
					</th>
					<th className="col">Статус</th>
					<th className="col">
						<Link
							title={direction === 'asc' ? 'Сортировка по возрастанию' : 'Сортировка по убыванию'}
							href={getSortLink('start')}
							preserveState
							className="whitespace-nowrap text-decoration-none"
						>
							Начало работы {sort === 'start' && (direction === 'asc' ? '▲' : '▼')}
						</Link>
					</th>
					<th className="col">
						<Link
							title={direction === 'asc' ? 'Сортировка по возрастанию' : 'Сортировка по убыванию'}
							href={getSortLink('end')}
							preserveState
							className="whitespace-nowrap text-decoration-none"
						>
							Сдача работы {sort === 'end' && (direction === 'asc' ? '▲' : '▼')}
						</Link>
					</th>
					<th className="col">ФИО пациента</th>
					<th className="col">Клиника</th>
					<th className="col">Техник</th>
					<th className="col">Блокировка</th>
					<th className="col">Виды работ</th>
					<th className="col text-center">Управление</th>
				</tr>
				</thead>
				<tbody>
				{props.items.map((item) => (
					<tr key={item.id}>
						<td>{item.id}</td>
						<td>{STATES[item.state] ?? 'UNKNOWN'}</td>
						<td>{item.start ?? 'UNKNOWN'}</td>
						<td>{item.end ?? 'UNKNOWN'}</td>
						<td>{item.patient ?? 'UNKNOWN'}</td>
						<td>{item.clinic?.name ?? 'UNKNOWN'}</td>
						<td>{item.mechanic?.name ?? 'UNKNOWN'}</td>
						<td>{getLockStatus(item)}</td>
						<td>
							<ul className="mb-0 ps-3">
								{item.work_types.map((type) => (
									<li key={type.id}>
										{type.name} {type.pivot?.count ? `(x${type.pivot.count})` : ''}
									</li>
								))}
							</ul>
						</td>
						<td>
                                <span className="flex gap-1 align-items-center justify-center">
                                    {!item.locked_both && (
										<Link
											className="i edit"
											title="Редактировать"
											href={`/works/edit/${item.id}`}
										/>
									)}
									{(item.locked_by_clinic || item.locked_by_mechanic) && (
										<a
											title="Снять блокировку"
											className="i unlock"
											onClick={() => openUnlockModal(item.id, item.locked_by_clinic, item.locked_by_mechanic)}
										/>
									)}
                                </span>
						</td>
					</tr>
				))}
				</tbody>
			</table>
		</TableBrowse>
	);
}
