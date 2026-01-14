import {Head, Link} from "@inertiajs/react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import TableBrowse from "@/Components/custom/Table";
export default function Browse(props: { prev_url: string, items: {id: number, name: string, cost: number }[] })
{
    return (<TableBrowse title="Список видов работ" urls={{prev: props.prev_url, add: '/works_types/create'}}>
        <table className="table">
            <thead>
            <tr>
                <th className="col">ID</th>
                <th className="col">Название</th>
                <th className="col">Стоимость</th>
                <th className="col text-center">Управление</th>
            </tr>
            </thead>
            <tbody>
            {
                props?.items?.map(item => <tr key={item.id}>
                    <td>{item.id}</td>
                    <td>{item.name}</td>
                    <td>{item.cost}</td>
                    <td>
                        <span className="flex gap-1 align-items-center justify-center">
                            <Link className="i edit" title="Редактировать" href={'/works_types/edit/' + item.id}></Link>
                            <Link className="i delete" title="Удалить" href={'/works_types/destroy/' + item.id}></Link>
                        </span>
                    </td>
                </tr>)
            }
            </tbody>
        </table>
    </TableBrowse>);
}
