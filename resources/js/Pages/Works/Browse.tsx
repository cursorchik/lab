import React, { useState } from 'react';
import { Form, Link, usePage } from '@inertiajs/react';
import TableBrowse from '@/Components/custom/Table';
import Modal from '../../Components/custom/modal/modal';
import styles from '../../Components/custom/modal/styles.module.scss';
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
};

type WorksProps = {
	prev_url: string;
	items: {
		id: number;
		start: string;
		end: string;
		state: number;
		patient: string;
		clinic: { id: number; name: string };
		mechanic: { id: number; name: string };
		work_types: {
			id: number;
			created_at: string | null;
			updated_at: string | null;
			name: string;
			cost: number;
			pivot: { work_id: number; work_type_id: number };
		}[];
		comment: string;
	}[];
	default_filters: Filters;
	filters: Filters;
	sort?: string;
	direction?: string;
};

export default function Browse(props: WorksProps)
{
	const [modal, setModal] = useState(false);
	const { sort = 'id', direction = 'asc' } = props;

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

	return (
		<TableBrowse urls={{ add: '/works/create' }} title="Список работ">
			<a className="btn btn-link" onClick={() => setModal(true)}>Фильтры</a>
			{modal && (
				<Modal close={() => setModal(false)}>
					<Form className={styles.filters} action="/" method="get" onBefore={() => setModal(false)}>
						<h3>Фильтры</h3>
						<div className={styles.filters_content}>
							<div>
								<div className="mb-3">
									<label htmlFor="date" className="form-label">Дата</label>
									<input type="date" className="form-control" id="date" name="date" defaultValue={props.filters.date ?? ''}/>
								</div>
							</div>
							<div>
								<div className="mb-3">
									<label htmlFor="patient" className="form-label">Пациент</label>
									<textarea name="patient" className="form-control" id="patient" defaultValue={props.filters.patient ?? ''}/>
								</div>
							</div>
							<div>
								<div className="mb-3">
									<label htmlFor="mid" className="form-label">Техник</label>
									<select className="form-select" id="mid" name="mid" defaultValue={props.filters.mechanics.selected}>
										<option value="0">Не выбрано</option>
										{props.filters.mechanics.items.map((item) => (<option key={item.value} value={item.value}>{item.label}</option>))}
									</select>
								</div>
							</div>
							<div>
								<div className="mb-3">
									<label htmlFor="cid" className="form-label">Клиника</label>
									<select className="form-select" id="cid" name="cid" defaultValue={props.filters.clinics.selected}>
										<option value="0">Не выбрано</option>{props.filters.clinics.items.map((item) => (<option key={item.id} value={item.id}>{item.name}</option>))}
									</select>
								</div>
							</div>
						</div>
						<div className="flex gap-1.5 justify-end">
							<Link className="btn btn-outline-dark" href="/" method="get" only={[]}>
								Сбросить
							</Link>
							<button className="btn btn-primary">Применить</button>
						</div>
					</Form>
				</Modal>
			)}
			<table className="table text-sm">
				<thead>
				<tr>
					<th className="col">
						<Link title={direction === 'asc' ? 'Сортировка по возрастанию' : 'Сортировка по убыванию'} href={getSortLink('id')} preserveState className="whitespace-nowrap text-decoration-none">
							ID {sort === 'id' && (direction === 'asc' ? '▲' : '▼')}
						</Link>
					</th>
					<th className="col">Статус</th>
					<th className="col">
						<Link title={direction === 'asc' ? 'Сортировка по возрастанию' : 'Сортировка по убыванию'} href={getSortLink('start')} preserveState className="whitespace-nowrap text-decoration-none">
							Начало работы {sort === 'start' && (direction === 'asc' ? '▲' : '▼')}
						</Link>
					</th>
					<th className="col">
						<Link title={direction === 'asc' ? 'Сортировка по возрастанию' : 'Сортировка по убыванию'} href={getSortLink('end')} preserveState className="whitespace-nowrap text-decoration-none">
							Сдача работы {sort === 'end' && (direction === 'asc' ? '▲' : '▼')}
						</Link>
					</th>
					<th className="col">ФИО пациента</th>
					<th className="col">Клиника</th>
					<th className="col">Техник</th>
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
						<td>
							<ul>
								{item.work_types.map((value) => (
									<li key={value.id}>{value.name}</li>
								))}
							</ul>
						</td>
						<td>
							<span className="flex gap-1 align-items-center justify-center">
								<Link className="i edit" title="Редактировать" href={'/works/edit/' + item.id} />
							</span>
						</td>
					</tr>
				))}
				</tbody>
			</table>
		</TableBrowse>
	);
}
