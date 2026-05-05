import {Link} from "@inertiajs/react";
import TableBrowse from "../../Components/custom/Table";

type Props = {
    prev_url: string
    items: {
        id      	: number,
        name    	: string,
		created_at	: string,
		updated_at	: string,
        salary		: number
    }[]
}

export default function Browse(props: Props)
{
	const formatMoney = (amount: number) : string => amount.toLocaleString('ru-RU');

    return (<TableBrowse title="Список клиник" urls={{add: '/clinics/create'}}>
        <table className="table text-sm">
            <thead>
            <tr>
                <th className="col">ID</th>
				<th className="col">Создано<br/>Изменено</th>
                <th className="col">Название</th>
                <th className="col">Счёт</th>
                <th className="col text-center">Управление</th>
            </tr>
            </thead>
            <tbody>
            {
                props.items.map(item => <tr key={item.id}>
                    <td>{item.id}</td>
					<td>{item.created_at}<br/>{item.updated_at}</td>
                    <td>{item.name}</td>
                    <td>{formatMoney(item.salary ?? 0)}</td>
                    <td>
                        <span className="flex gap-1 align-items-center justify-center">
                            <Link type="link" className="i invoice" method="post" title="Выставить счёт" href="/clinics/invoice" data={{id: item.id}} />
                            <Link className="i edit" title="Редактировать" href={'/clinics/edit/' + item.id}></Link>
                        </span>
                    </td>
                </tr>)
            }
            </tbody>
        </table>
    </TableBrowse>);
}
