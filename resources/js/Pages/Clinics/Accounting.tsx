import { Link } from "@inertiajs/react";
import WorkLayout from "@/Layouts/work/WorkLayout";

type Props = {
    prev_url    : string,
    id          : number,
    url         : string,
    date        : string,
    name        : number,
    items       : { start: string, patient: string, name: string, cost: number, count: number, salary: number }[],
}
export default function Accounting(props: Props)
{
    const date = new Date(props.date);

    const preZero = (value: number) => value < 10 ? '0' + value : value;

    const dateStr = date.getFullYear().toString() + preZero(date.getMonth() + 1) + preZero(date.getDate());

    window.history.replaceState(
        null,
        '',
        '/clinics/invoice/' + props.id + '/' + dateStr
    );

    const m = [
        'январь',
        'февраль',
        'март',
        'апрель',
        'май',
        'июнь',
        'июль',
        'август',
        'сентябрь',
        'октябрь',
        'ноябрь',
        'декабрь'

    ];

    return (<WorkLayout title={'Список клиник / Выставление счёта за ' + m[date.getMonth()] + ' ' + date.getFullYear() + ' клиника ' + props.name + ' '}>
        <Link className="btn btn-link" method="post" href={'/clinics'} data={{
            filters: {
                date: `${date.getFullYear().toString()}-${preZero(date.getMonth() + 1)}-${preZero(date.getDate())}`
            }
        }}>Назад</Link>
        <a className="btn btn-link" onClick={() => window.print()}>Распечатать</a>

        <h6>Реестр выполненых работ за {m[date.getMonth()]} Клиника {props.name} {date.getFullYear()}</h6>

        <table className="table table-hover mt-4">
            <thead>
                <tr>
                    <th>Дата заказ-наряда</th>
                    <th>Ф.И.О пациента</th>
                    <th>Изделие</th>
                    <th>Цена за ед.</th>
                    <th>Кол-во ед.</th>
                    <th>Итого сумма по изделиям</th>
                    <th>Сумма по заказу</th>
                </tr>
            </thead>
            <tbody>
            {
                props?.items?.map((item, index) => <tr key={index}>
                    <td>{item.start}</td>
                    <td>{item.patient}</td>
                    <td>{item.name}</td>
                    <td>{item.cost}</td>
                    <td>{item.count}</td>
                    <td>{item.salary}</td>
                    <td>{item.salary}</td>
                </tr>) ?? <tr><td colSpan={7} className="text-center">Данные отсутствуют</td></tr>
            }
            </tbody>
        </table>
    </WorkLayout>);
}
