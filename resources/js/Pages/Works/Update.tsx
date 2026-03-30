import { Link, usePage, useForm } from "@inertiajs/react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import React from "react";
import ErrorHint from '@/Components/custom/Error';
import { MultiSelectQuantity, SelectedItem } from '@/Components/custom/MultiSelectQuantity';

type WorksProps = {
	prev_url: string,
	data: {
		item: {
			id: number,
			start: string,
			end: string,
			patient: string,
			state: number,
			cid: number,
			mid: number,
			comment: string,
		},
		states: string[],
		clinics: { id: number, name: string }[],
		mechanics: { id: number, name: string }[],
		works_types: { id: number, name: string, cost: number }[],
		current_work_types: SelectedItem[]; // { id: number, quantity: number }[]
	}
}

export default function Update(props: WorksProps)
{
	const { errors, flash } = usePage().props;

	const { data, setData, post, processing } = useForm({
		start	: props.data.item.start,
		end		: props.data.item.end,
		state	: props.data.item.state,
		patient	: props.data.item.patient,
		cid		: props.data.item.cid,
		mid		: props.data.item.mid,
		comment	: props.data.item.comment ?? '',
		works	: props.data.current_work_types
	});

	const handleSubmit = (e: React.FormEvent) =>
	{
		e.preventDefault();
		post(`/works/update/${props.data.item.id}`);
	};

	return (
		<WorkLayout title="Список работ / Изменение" flash={flash}>
			<Link href="/" className="btn btn-link">Назад</Link>
			<Link
				title="Удалить"
				preserveScroll
				className="btn btn-link"
				href={'/works/destroy/' + props.data.item.id}
				onClick={(e) => { if (!confirm('Удалить работу?')) e.preventDefault(); }}
			>
				Удалить
			</Link>
			<form className="mt-4" onSubmit={handleSubmit}>
				<div className="flex flex-col justify-center min-w-60 max-w-screen-md">
					{/* Состояние */}
					<div className="mb-3">
						<label htmlFor="state" className="form-label">Состояние</label>
						<select
							id="state"
							value={data.state}
							className="form-select"
							onChange={e => setData('state', Number(e.target.value))}
						>
							<option value={-1}>Не указано</option>
							{props.data.states?.map((item, index) => (<option key={index} value={index}>{item}</option>))}
						</select>
						<ErrorHint text={errors.state} />
					</div>

					{/* Дата начала */}
					<div className="mb-3">
						<label htmlFor="start" className="form-label">Дата начала</label>
						<input
							type="date"
							id="start"
							className="form-control"
							value={data.start}
							onChange={e => setData('start', e.target.value)}
						/>
						<ErrorHint text={errors.start} />
					</div>

					{/* Дата сдачи */}
					<div className="mb-3">
						<label htmlFor="end" className="form-label">Дата сдачи</label>
						<input
							id="end"
							type="date"
							value={data.end}
							className="form-control"
							onChange={e => setData('end', e.target.value)}
						/>
						<ErrorHint text={errors.end} />
					</div>

					{/* Пациент */}
					<div className="mb-3">
						<label htmlFor="patient" className="form-label">ФИО пациента</label>
						<textarea
							id="patient"
							value={data.patient}
							className="form-control"
							onChange={e => setData('patient', e.target.value)}
						/>
						<ErrorHint text={errors.patient} />
					</div>

					{/* Клиника */}
					<div className="mb-3">
						<label htmlFor="clinic" className="form-label">Название клиники</label>
						<select
							id="clinic"
							value={data.cid}
							className="form-select"
							onChange={e => setData('cid', Number(e.target.value))}
						>
							<option value={0}>Не указано</option>
							{props.data.clinics?.map(item => (<option key={item.id} value={item.id}>{item.name}</option>))}
						</select>
						<ErrorHint text={errors.cid} />
					</div>

					{/* Механик */}
					<div className="mb-3">
						<label htmlFor="mechanic" className="form-label">Техник</label>
						<select
							id="mechanic"
							value={data.mid}
							className="form-select"
							onChange={e => setData('mid', Number(e.target.value))}
						>
							<option value={0}>Не указано</option>
							{props.data.mechanics?.map(item => (<option key={item.id} value={item.id}>{item.name}</option>))}
						</select>
						<ErrorHint text={errors.mid} />
					</div>

					{/* Типы работ с количеством */}
					<div className="mb-3">
						<label htmlFor="works_types" className="form-label">Типы работ</label>
						<MultiSelectQuantity
							value={data.works}
							options={props.data.works_types}
							onChange={(val) => setData('works', val)}
							placeholder="Выберите типы работ"
						/>
						<ErrorHint text={errors.works} />
					</div>

					{/* Комментарий */}
					<div className="mb-3">
						<label htmlFor="comment" className="form-label">Комментарий</label>
						<textarea id="comment" className="form-control" value={data.comment} onChange={e => setData('comment', e.target.value)}/>
						<ErrorHint text={errors.comment} />
					</div>

					<button className="btn btn-primary" type="submit" disabled={processing}>Применить</button>
				</div>
			</form>
		</WorkLayout>
	);
}
