import TableBrowse from "../../Components/custom/Table";
import {Form, Link} from "@inertiajs/react";
import React, {ChangeEvent, useState} from 'react'
import axios from "axios";

type Filters = {
    date: string
}

type MechanicsProps = {
    prev_url: string,
    items: {
        id: number,
        name: string,
        created_at: string,
        updated_at: string,
        salary: number
    }[],
    default_filters: Filters,
    filters: Filters
}
export default function Browse(props: MechanicsProps)
{
    const [data, setData] = useState(props.items ?? []);
    const [date, setDate] = useState(props.filters?.date ?? props.default_filters?.date ?? new Date());

    return (<TableBrowse title="Список техников" urls={{add: '/mechanics/create'}}>
        <a className="inline-block">
            <input
                id="date"
                type="date"
                value={date}
                className="form-control form-control-sm w-auto"
                onChange={ (e: ChangeEvent<HTMLInputElement>) =>
                    {
                        setDate(e.target.value);
                        axios.post('/mechanics/data', { filters: { date: e.target.value } }).then(r => setData(r.data.items));
                    }
                }
            />
        </a>

        <table className="table">
            <thead>
            <tr>
                <th className="col">ID</th>
                <th className="col">Создано<br/>Изменено</th>
                <th className="col">Название</th>
                <th className="col">Зряплата</th>
                <th className="col text-center">Управление</th>
            </tr>
            </thead>
            <tbody>
            {
                data.map(item => <tr key={item.id}>
                    <td>{item.id}</td>
                    <td>{item.created_at}<br/>{item.updated_at}</td>
                    <td>{item.name}</td>
                    <td>{item.salary ?? 0}</td>
                    <td className="text-center">
                        <span className="flex gap-1 align-items-center justify-center">
                            { date && <Link type="link" className="i invoice" method="post" title="Выставить счёт" href={"/mechanics/invoice"} data={{id: item.id, date: date}} /> }
                            <Link className="i edit" title="Редактировать" href={'/mechanics/edit/' + item.id}></Link>
                            <Link onClick={() => confirm('Удалить клинику?')} className="i delete" title="Удалить" href={'/mechanics/destroy/' + item.id}></Link>
                        </span>
                    </td>
                </tr>)
            }
            </tbody>
        </table>
    </TableBrowse>);
}
