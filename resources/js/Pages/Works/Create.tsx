import {Link, Form, usePage, useForm} from "@inertiajs/react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import React, {useState} from "react";
import Select from "react-select";
import ErrorHint from '@/Components/custom/Error'
import {MultiSelectQuantity, SelectedItem} from '@/Components/custom/MultiSelectQuantity'

type WorksProps = {
    prev_url: string,
    clinics: {id: number, name: string }[],
    mechanics: {id: number, name: string }[],
    works_types: {id: number, name: string, cost: number }[],
}

export default function Create(props: WorksProps)
{
	const pageErrors = usePage().props.errors;

	const { data, setData, post, processing, errors } = useForm({
		start: '',
		end: '',
		count: '',
		state: 0,
		patient: '',
		works: [] as SelectedItem[],
		cid: 0,
		mid: 0,
		comment: '',
	});

	const handleSubmit = (e: React.FormEvent) => { e.preventDefault(); post('/works/store'); };

	return (
		<WorkLayout title="Список работ / Создание">
			<Link href="/" className="btn btn-link">Назад</Link>
			<form className="mt-1" onSubmit={handleSubmit}>
				<div className="flex flex-col justify-center min-w-60 max-w-screen-md">
					<div className="mb-3">
						<label htmlFor="start" className="form-label">Дата начала</label>
						<input
							id="start"
							type="date"
							value={data.start}
							className="form-control"
							onChange={e => setData('start', e.target.value)}
						/>
						<ErrorHint text={pageErrors.start} />
					</div>

					<div className="mb-3">
						<label htmlFor="end" className="form-label">Дата сдачи</label>
						<input
							id="end"
							type="date"
							value={data.end}
							className="form-control"
							onChange={e => setData('end', e.target.value)}
						/>
						<ErrorHint text={pageErrors.end} />
					</div>

					<div className="mb-3">
						<input type="hidden" name="state" value="0" />
					</div>

					<div className="mb-3">
						<label htmlFor="patient" className="form-label">ФИО пациента</label>
						<textarea
							id="patient"
							value={data.patient}
							className="form-control"
							onChange={e => setData('patient', e.target.value)}
						/>
						<ErrorHint text={pageErrors.patient} />
					</div>

					<div className="mb-3">
						<label htmlFor="works_types" className="form-label">Тип работы</label>
						<MultiSelectQuantity
							value={data.works}
							options={props.works_types}
							onChange={(val) => setData('works', val)}
							placeholder="Выберите тип работ(ы)"
						/>
						<ErrorHint text={pageErrors.works} />
					</div>

					<div className="mb-3">
						<label htmlFor="clinic" className="form-label">Название клиники</label>
						<select
							id="clinic"
							value={data.cid}
							className="form-select"
							onChange={e => setData('cid', Number(e.target.value))}
						>
							<option value="0">Не указано</option>
							{props.clinics?.map(item => (<option key={item.id} value={item.id}>{item.name}</option>))}
						</select>
						<ErrorHint text={pageErrors.cid} />
					</div>

					<div className="mb-3">
						<label htmlFor="mechanic" className="form-label">Техник</label>
						<select
							id="mechanic"
							value={data.mid}
							className="form-select"
							onChange={e => setData('mid', Number(e.target.value))}
						>
							<option value="0">Не указано</option>
							{props.mechanics?.map(item => (<option key={item.id} value={item.id}>{item.name}</option>))}
						</select>
						<ErrorHint text={pageErrors.mid} />
					</div>

					<div className="mb-3">
						<label htmlFor="comment" className="form-label">Комментарий</label>
						<textarea
							id="comment"
							value={data.comment}
							className="form-control"
							onChange={e => setData('comment', e.target.value)}
						/>
						<ErrorHint text={pageErrors.comment} />
					</div>

					<button className="btn btn-primary" type="submit" disabled={processing}>Добавить</button>
				</div>
			</form>
		</WorkLayout>
	);
}
