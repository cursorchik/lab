import {Link, router} from "@inertiajs/react";
import {useEffect, useState} from "react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import {useAccounting} from "@/hooks/useAccounting";
import {AccountingTable, ColumnConfig} from "@/Components/custom/AccountingTable";

interface WorkDetail
{
	id: number;
	start: string;
	patient: string;
	name: string;
	cost: number;
	count: number;
	salary: number;
}

type Props = {
	prev_url?: string;
	id: number;
	url?: string;
	name: string;
	items: Record<number, WorkDetail[]>;
};

export default function Accounting(props: Props)
{
	const {
		selectedIds,
		allSelected,
		totalCount,
		totalAmount,
		handleSelectAll,
		handleSelectWork,
		isWorkSelected,
	} = useAccounting(props.items);

	const [periodFrom, setPeriodFrom] = useState('');
	const [periodTo, setPeriodTo] = useState('');

	const formatDate = (dateStr: string) =>
	{
		if (!dateStr) return '';
		const [year, month, day] = dateStr.split('-');
		return `${day}.${month}.${year}`;
	};

	const handleLockAndPrint = () =>
	{
		if (selectedIds.length === 0)
		{
			alert('Выберите хотя бы одну работу для блокировки.');
			return;
		}

		router.post("/mechanics/lock-works", {
			mechanic_id: props.id,
			work_ids: selectedIds,
		}, {
			preserveScroll: true,
			onSuccess: () =>
			{
				window.print();
				setTimeout(() => window.location.reload(), 500);
			},
			onError: (errors) =>
			{
				console.error('Ошибка блокировки:', errors);
				alert('Ошибка блокировки: ' + JSON.stringify(errors));
			},
		});
	};

	useEffect(() =>
	{
		const urlParts = window.location.pathname.split('/');
		if (urlParts.length === 3 && urlParts[urlParts.length - 1] === props.id.toString()) return;
		window.history.replaceState(null, '', `${window.location.href}/${props.id.toString()}`);
	}, [props.id]);

	// Для механиков используем стандартный рендер (он уже в AccountingTable)
	const columns: ColumnConfig[] = [
		{key: 'checkbox', header: '', className: 'no-print'},
		{key: 'patient', header: 'Ф.И.О пациента'},
		{key: 'start', header: 'Дата'},
		{key: 'name', header: 'Изделие'},
		{key: 'cost', header: 'Цена за ед.'},
		{key: 'count', header: 'Кол-во ед.'},
		{key: 'salary', header: 'Итого'},
	];

	return (
		<WorkLayout title={`Зарплатная ведомость - ${props.name}`}>
			<div className="d-flex justify-content-between no-print">
				<Link href="/mechanics" method="post" className="btn btn-link">Назад</Link>
				<div>
					<a className="btn btn-link no-print" onClick={() => window.print()}>Распечатать без блокировки</a>
					<button className="btn btn-primary no-print" onClick={handleLockAndPrint}>Заблокировать выбранные и распечатать</button>
				</div>
			</div>

			<div className="mb-3 mt-3 no-print">
				<label className="me-2">Период для отображения в печати:</label>
				<input
					type="date"
					value={periodFrom}
					onChange={e => setPeriodFrom(e.target.value)}
					className="form-control d-inline-block w-auto me-2"
				/>
				<span>—</span>
				<input
					type="date"
					value={periodTo}
					onChange={e => setPeriodTo(e.target.value)}
					className="form-control d-inline-block w-auto ms-2"
				/>
			</div>

			<h6>
				Зарплатная ведомость
				{periodFrom && periodTo
					? ` за период с ${formatDate(periodFrom)} по ${formatDate(periodTo)}`
					: ' за выбранный месяц'}
				– Техник {props.name}
			</h6>

			<AccountingTable
				items={props.items}
				allSelected={allSelected}
				totalCount={totalCount}
				totalAmount={totalAmount}
				handleSelectAll={handleSelectAll}
				handleSelectWork={handleSelectWork}
				isWorkSelected={isWorkSelected}
				columns={columns}
			/>

			<style>{`
                @media print {
                    .no-print {
                        display: none;
                    }
                    .row-not-selected {
                        display: none;
                    }
                }
            `}</style>
		</WorkLayout>
	);
}
