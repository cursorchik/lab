import {Link, Form, usePage} from "@inertiajs/react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import React from "react";

type WorksProps = {
    prev_url: string,
    data: {
        item : {
            id          : number,
            start       : string,
            end         : string,
            patient     : string,
            count       : number,
            state       : number
            cid         : number,
            mid         : number,
            wtid        : number,
            comment     : string,
        },
        states      : string[],
        clinics     : {id: number, name: string }[],
        mechanics   : {id: number, name: string }[],
        works_types : {id: number, name: string, cost: number }[],
    }
}

export default function Update(props: WorksProps)
{
    const { errors, flash } = usePage().props;

    return (<WorkLayout title="Список работ / Изменение" flash={flash}>
        { props.prev_url && <Link href={props.prev_url ?? '/'}>Назад</Link> }
        <Form disableWhileProcessing className="mt-4" action={ '/works/update/' + props.data.item.id } method="post">
            <div className="flex flex-col justify-center min-w-60 max-w-screen-md">
                <div className="mb-3">
                    <label htmlFor="state" className="form-label">Состояние</label>
                    <select className="form-select" name="state" id="state" onChange={e => console.log(e.target.value)}>
                        <option value="-1">Не указано</option>
                        {props.data.states?.map((item, index) => <option key={index} selected={index == props.data.item.state} value={index}>{item}</option>)}
                    </select>
                    {errors.state && (<div>{errors.state}</div>)}
                </div>

                <div className="mb-3">
                    <label htmlFor="start" className="form-label">Дата начала</label>
                    <input type="date" name="start" defaultValue={props.data.item.start} className="form-control" id="start"/>
                    {errors.start && (<div>{errors.start}</div>)}
                </div>

                <div className="mb-3">
                    <label htmlFor="end" className="form-label">Дата сдачи</label>
                    <input type="date" name="end" defaultValue={props.data.item.end} className="form-control" id="end"/>
                    {errors.end && (<div>{errors.end}</div>)}
                </div>

                <div className="mb-3">
                    <label htmlFor="count" className="form-label">Кол-во</label>
                    <input type="number" name="count" defaultValue={props.data.item.count} className="form-control" id="count"/>
                    {errors.count && (<div>{errors.count}</div>)}
                </div>

                <div className="mb-3">
                    <label htmlFor="patient" className="form-label">ФИО пациента</label>
                    <textarea className="form-control" name="patient" id="patient" defaultValue={props.data.item.patient}/>
                    {errors.patient && (<div>{errors.patient}</div>)}
                </div>

                <div className="mb-3">
                    <label htmlFor="clinic" className="form-label">Название клиники</label>
                    <select className="form-select" name="cid" id="clinic">
                        <option value="0">Не указано</option>
                        {props.data.clinics?.map(item => <option key={item.id} selected={item.id == props.data.item.cid} value={item.id}>{item.name}</option>)}
                    </select>
                    {errors.cid && (<div>{errors.cid}</div>)}
                </div>

                <div className="mb-3">
                    <label htmlFor="mechanic" className="form-label">Техник</label>
                    <select className="form-select" name="mid" id="mechanic">
                        <option value="0">Не указано</option>
                        {props.data.mechanics?.map(item => <option key={item.id} selected={item.id == props.data.item.mid} value={item.id}>{item.name}</option>)}
                    </select>
                    {errors.mid && (<div>{errors.mid}</div>)}
                </div>

                <div className="mb-3">
                    <label htmlFor="works_types" className="form-label">Тип работы</label>
                    <select className="form-select" name="wtid" id="works_types">
                        <option value="0">Не указано</option>
                        {props.data.works_types?.map(item => <option key={item.id} selected={item.id == props.data.item.wtid} value={item.id}>{item.name}</option>)}
                    </select>
                    {errors.wtid && (<div>{errors.wtid}</div>)}
                </div>

                <div className="mb-3">
                    <label htmlFor="comment" className="form-label">Комментарий</label>
                    <textarea className="form-control" name="comment" id="comment" defaultValue={props.data.item.comment}/>
                    {errors.comment && (<div>{errors.comment}</div>)}
                </div>

                <button className="btn btn-primary" type="submit">Применить</button>
            </div>
        </Form>
    </WorkLayout>);
}
