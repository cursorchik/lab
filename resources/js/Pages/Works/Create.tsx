import {Link, Form, usePage} from "@inertiajs/react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import React, {useState} from "react";
import Select from "react-select";

type WorksProps = {
    prev_url: string,
    clinics: {id: number, name: string }[],
    mechanics: {id: number, name: string }[],
    works_types: {id: number, name: string, cost: number }[],
}

export default function Create(props: WorksProps)
{
    const { errors } = usePage().props;

    const [selectedOption, setSelectedOption] = useState(null);

    return (<WorkLayout title="Список работ / Создание">
        <Link href="/" className="btn btn-link">Назад</Link>
        <Form className="mt-1" action="/works/store" method="post">
            <div className="flex flex-col justify-center min-w-60 max-w-screen-md">
                <div className="mb-3">
                    <label htmlFor="start" className="form-label">Дата начала</label>
                    <input type="date" name="start" className="form-control" id="start" aria-describedby="startHelp"/>
                    {errors.start && (<div>{errors.start}</div>)}
                </div>

                <div className="mb-3">
                    <label htmlFor="end" className="form-label">Дата сдачи</label>
                    <input type="date" name="end" className="form-control" id="end" aria-describedby="endHelp"/>
                    {errors.end && (<div>{errors.end}</div>)}
                </div>

                <div className="mb-3">
                    <label htmlFor="count" className="form-label">Кол-во единиц</label>
                    <input type="number" name="count" className="form-control" id="count" aria-describedby="countHelp"/>
                    {errors.count && (<div>{errors.count}</div>)}
                </div>

                <div className="mb-3">
                    <input type="hidden" name="state" value="0"/>
                </div>

                <div className="mb-3">
                    <label htmlFor="patient" className="form-label">ФИО пациента</label>
                    <textarea className="form-control" name="patient" id="patient" aria-describedby="patientHelp"/>
                    {errors.patient && (<div>{errors.patient}</div>)}
                </div>

                <div className="mb-3">
                    <label htmlFor="works_types" className="form-label">Тип работы</label>
                    <select className="form-select" name="wtid" id="works_types">
                        <option selected value="0">Не указано</option>
                        {props.works_types?.map(item => <option value={item.id}>{item.name}</option>)}
                    </select>
                    {errors.wtid && (<div>{errors.wtid}</div>)}
                </div>

                <div className="mb-3">
                    <label htmlFor="clinic" className="form-label">Название клиники</label>
                    <select className="form-select" name="cid" id="clinic">
                        <option selected value="0">Не указано</option>
                        {props.clinics?.map(item => <option value={item.id}>{item.name}</option>)}
                    </select>
                    {errors.cid && (<div>{errors.cid}</div>)}
                </div>

                <div className="mb-3">
                    <label htmlFor="mechanic" className="form-label">Техник</label>
                    <select className="form-select" name="mid" id="mechanic">
                        <option selected value="0">Не указано</option>
                        {props.mechanics?.map(item => <option key={item.id} value={item.id}>{item.name}</option>)}
                    </select>
                    {errors.mid && (<div>{errors.mid}</div>)}
                </div>

                <div className="mb-3">
                    <label htmlFor="comment" className="form-label">Комментарий</label>
                    <textarea className="form-control" name="comment" id="comment" aria-describedby="commentHelp"/>
                    {errors.comment && (<div>{errors.comment}</div>)}
                </div>

                <button className="btn btn-primary" type="submit">Добавить</button>
            </div>
        </Form>
    </WorkLayout>);
}
