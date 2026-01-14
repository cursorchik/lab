import {Form, Link, } from "@inertiajs/react";
import { Inertia } from '@inertiajs/inertia';
import TableBrowse from "@/Components/custom/Table";
import React, {useState} from 'react'
import Modal from '../../Components/custom/modal/modal';

import styles from '../../Components/custom/modal/styles.module.scss';
import Select, {ActionMeta, MultiValue} from 'react-select'

type OptionType = {
    value: string;
    label: string;
}

type Filters = {
    date: string
    patient: string
    mechanics: {
        selected: number,
        items: OptionType[]
    }
    clinics: {
        selected: number,
        items: { id: number, name: string }[]
    }
}

type WorksProps = {
    prev_url: string
    items: {
        id          : number
        start       : string
        end         : string
        count       : number
        state       : number
        patient     : string
        clinic      : { id: number, name: string }
        mechanic    : { id: number, name: string }
        work_type   : { id: number, name: string, cost: number }
        comment     : string
    }[],
    default_filters: Filters,
    filters: Filters
}
export default function Browse(props: WorksProps)
{
    const [modal, setModal] = useState(false);

    return (<TableBrowse urls={{add: '/works/create'}} title="Список работ">
        <a className="btn btn-link" onClick={ () => setModal(true) }>Фильтры</a>
        { modal && <Modal close={ () => setModal(false) }>
            <Form className={styles.filters} action="/" method="post" onBefore={ () => { setModal(false); } }>
                <h3>Фильтры</h3>
                <div className={styles.filters_content}>
                    <div>
                        <div className="mb-3">
                            <label htmlFor="date" className="form-label">Дата</label>
                            <input type="date" onChange={(e) => console.log(e.target.value)} className="form-control" id="date" name="filters[date]" defaultValue={props.filters.date ?? new Date()}/>
                        </div>
                    </div>
                    <div>
                        <div className="mb-3">
                            <label htmlFor="patient" className="form-label">Пациент</label>
                            <textarea name="filters[patient]" className="form-control" id="patient">{props.filters.patient ?? ''}</textarea>
                        </div>
                    </div>
                    <div>
                        <div className="mb-3">
                            <label htmlFor="mid" className="form-label">Техник</label>
                            <select className="form-select" id="cid" name="filters[mid]" defaultValue={props.filters?.mechanics?.selected ?? 0}>
                                <option value="0">Не выбрано</option>
                                {
                                    props?.filters?.mechanics?.items.map((item) => (<option value={item.value} key={item.value}>
                                        {item.label}
                                    </option>))
                                }
                            </select>
                        </div>
                    </div>
                    <div>
                        <div className="mb-3">
                            <label htmlFor="cid" className="form-label">Клиника</label>
                            <select className="form-select" id="cid" name="filters[cid]" defaultValue={props.filters.clinics.selected ?? 0}>
                                <option value="0">Не выбрано</option>
                                {
                                    props?.filters?.clinics?.items.map((item) => (<option /*selected={item.id == props.filters.mechanics.selected}*/ value={item.id} key={item.id}>
                                        {item.name}
                                    </option>))
                                }
                            </select>
                        </div>
                    </div>
                </div>
                <div className="flex gap-1.5 justify-end">
                    <Link className="btn btn-outline-dark" href="/" data={{filters: props.default_filters}} method="post">Сброить</Link>
                    <button className="btn btn-primary">Применить</button>
                </div>
            </Form>
        </Modal> }
        <table className="table">
            <thead>
                <tr>
                    <th className="col">ID</th>
                    <th className="col">Начало работы</th>
                    <th className="col">Сдача работы</th>
                    <th className="col">Фио пациента</th>
                    <th className="col">Клиника (название)</th>
                    <th className="col">Техник</th>
                    <th className="col">Вид работы</th>
                    <th className="col">Кол-во</th>
                    <th className="col text-center">Управление</th>
                </tr>
            </thead>
            <tbody>
            {
                props?.items?.map(item => <tr key={item.id}>
                    <td>{item.id ?? 'UNKNOWN'}</td>
                    <td>{item.start ?? 'UNKNOWN'}</td>
                    <td>{item.end ?? 'UNKNOWN'}</td>
                    <td>{item.patient ?? 'UNKNOWN'}</td>
                    <td>{item.clinic?.name ?? 'UNKNOWN'}</td>
                    <td>{item.mechanic?.name ?? 'UNKNOWN'}</td>
                    <td>{item.work_type?.name ?? 'UNKNOWN'}</td>
                    <td>{item.count ?? 'UNKNOWN'}</td>
                    <td>
                        <span className="flex gap-1 align-items-center justify-center">
                            <Link className="i edit" title="Редактировать" href={'/works/edit/' + item.id}></Link>
                        </span>
                    </td>
                </tr>)
            }
            </tbody>
        </table>
    </TableBrowse>);
}
