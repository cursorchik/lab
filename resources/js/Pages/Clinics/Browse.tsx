import {Form, Link, usePage} from "@inertiajs/react";
import TableBrowse from "../../Components/custom/Table";
import Modal from "@/Components/custom/modal/modal";
import styles from "@/Components/custom/modal/styles.module.scss";
import React, {ChangeEvent, useEffect, useState} from "react";
import {preview} from "vite";
import {Inertia} from "@inertiajs/inertia";
import axios from "axios";

type Filters = {
    date: string
}

type BrowseProps = {
    prev_url: string
    items: {
        id      : number
        name    : string
        salary  : number
    }[],
    default_filters: Filters,
    filters: Filters
}

export default function Browse(browseProps: BrowseProps)
{
    const [data, setData] = useState(browseProps.items ?? []);
    const [date, setDate] = useState(browseProps.filters?.date ?? browseProps.default_filters?.date ?? new Date());

    return (<TableBrowse title="Список клиник" urls={{add: '/clinics/create'}}>
        <a className="inline-block">
            <input
                id="date"
                type="date"
                value={date}
                className="form-control form-control-sm w-auto"
                onChange={ (e: ChangeEvent<HTMLInputElement>) =>
                    {
                        setDate(e.target.value);
                        axios.post('/clinics/data', { filters: { date: e.target.value } }).then(r => setData(r.data.items));
                    }
                }
            />
        </a>
        <table className="table">
            <thead>
            <tr>
                <th className="col">ID</th>
                <th className="col">Название</th>
                <th className="col">Счёт</th>
                <th className="col text-center">Управление</th>
            </tr>
            </thead>
            <tbody>
            {
                data.map(item => <tr key={item.id}>
                    <td>{item.id}</td>
                    <td>{item.name}</td>
                    <td>{item.salary ?? 0}</td>
                    <td>
                        <span className="flex gap-1 align-items-center justify-center">
                            { date && <Link type="link" className="i invoice" method="post" title="Выставить счёт" href="/clinics/invoice/" data={{id: item.id, date: date}} /> }
                            <Link className="i edit" title="Редактировать" href={'/clinics/edit/' + item.id}></Link>
                            <Link onClick={() => confirm('Удалить клинику?')} className="i delete" title="Удалить" href={'/clinics/destroy/' + item.id}></Link>
                        </span>
                    </td>
                </tr>)
            }
            </tbody>
        </table>
    </TableBrowse>);
}
