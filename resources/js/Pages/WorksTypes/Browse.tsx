import {Head, Link} from "@inertiajs/react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import TableBrowse from "@/Components/custom/Table";
export default function Browse(props: { prev_url: string, items: {id: number, name: string, cost_clinic: number, cost_mechanic: number }[] })
{
	const formatMoney = (amount: number) : string => amount.toLocaleString('ru-RU');

    return (<TableBrowse title="Список видов работ" urls={{prev: props.prev_url, add: '/works_types/create'}}>
        <table className="table text-sm">
            <thead>
            <tr>
                <th className="col">ID</th>
                <th className="col">Название</th>
                <th className="col whitespace-nowrap" title="Стоимость для клиники / Стоимость для техника">Стоимость (кл/мех)</th>
                <th className="col text-center">Управление</th>
            </tr>
            </thead>
            <tbody>
            {
                props?.items?.map(item => <tr key={item.id}>
                    <td>{item.id}</td>
                    <td>{item.name}</td>
                    <td>{formatMoney(item.cost_clinic)} / {formatMoney(item.cost_mechanic)}</td>
                    <td>
                        <span className="flex gap-1 align-items-center justify-center">
                            <Link className="i edit" title="Редактировать" href={'/works_types/edit/' + item.id}></Link>
                        </span>
                    </td>
                </tr>)
            }
            </tbody>
        </table>
    </TableBrowse>);
}
