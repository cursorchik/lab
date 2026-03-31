import { Link, router } from "@inertiajs/react";
import { useEffect } from "react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import { useAccounting } from "@/hooks/useAccounting";
import { AccountingTable, ColumnConfig } from "@/Components/custom/AccountingTable";

interface WorkDetail {
	id: number;
	start: string;
	patient: string;
	name: string;
	cost: number;
	count: number;
	salary: number;
}

type Props = {
	prev_url: string;
	id: number;
	url: string;
	name: string;
	items: Record<number, WorkDetail[]>;
};

export default function Accounting(props: Props) {
	const {
		selectedIds,
		allSelected,
		totalCount,
		totalAmount,
		handleSelectAll,
		handleSelectWork,
		isWorkSelected,
	} = useAccounting(props.items);

	const handleLockAndPrint = () => {
		if (selectedIds.length === 0) {
			alert('Выберите хотя бы одну работу для блокировки.');
			return;
		}

		router.post("/clinics/lock-works", {
			clinic_id: props.id,
			work_ids: selectedIds,
		}, {
			preserveScroll: true,
			onSuccess: () => {
				window.print();
				setTimeout(() => window.location.reload(), 500);
			},
			onError: (errors) => {
				console.error('Ошибка блокировки:', errors);
				alert('Ошибка блокировки: ' + JSON.stringify(errors));
			},
		});
	};

	useEffect(() => {
		const urlParts = window.location.pathname.split('/');
		if (urlParts.length === 3 && urlParts[urlParts.length - 1] === props.id.toString()) return;
		window.history.replaceState(null, '', `${window.location.href}/${props.id.toString()}`);
	}, [props.id]);

	// Кастомный рендер для колонок клиники
	const renderCell = (
		item: WorkDetail,
		column: ColumnConfig,
		isFirstInWork: boolean,
		totalSalary: number
	) => {
		switch (column.key) {
			case 'checkbox':
				return isFirstInWork ? (
					<input
						type="checkbox"
						checked={isWorkSelected(item.id)}
						onChange={() => handleSelectWork(item.id)}
					/>
				) : null;
			case 'start':
				return isFirstInWork ? item.start : '';
			case 'patient':
				return isFirstInWork ? item.patient : '';
			case 'name':
				return item.name;
			case 'cost':
				return item.cost;
			case 'count':
				return item.count;
			case 'item_salary':
				return item.salary;
			case 'total_salary':
				return isFirstInWork ? totalSalary : '';
			default:
				return '';
		}
	};

	const columns: ColumnConfig[] = [
		{ key: 'checkbox', header: '', className: 'no-print' },
		{ key: 'start', header: 'Дата заказ-наряда' },
		{ key: 'patient', header: 'Ф.И.О пациента' },
		{ key: 'name', header: 'Изделие' },
		{ key: 'cost', header: 'Цена за ед.' },
		{ key: 'count', header: 'Кол-во ед.' },
		{ key: 'item_salary', header: 'Итого сумма по изделиям' },
		{ key: 'total_salary', header: 'Сумма по заказу' },
	];

	return (
		<WorkLayout title={`Счёт - ${props.name}`}>
			<div className="d-flex justify-content-between no-print">
				<Link href="/clinics" method="post" className="btn btn-link">Назад</Link>
				<button className="btn btn-primary" onClick={handleLockAndPrint}>
					Заблокировать выбранные и распечатать
				</button>
				<a className="btn btn-link" onClick={() => window.print()}>
					Распечатать без блокировки
				</a>
			</div>

			<h6>Реестр выполненных работ за выбранный месяц – Клиника {props.name}</h6>

			<AccountingTable
				items={props.items}
				allSelected={allSelected}
				totalCount={totalCount}
				totalAmount={totalAmount}
				handleSelectAll={handleSelectAll}
				handleSelectWork={handleSelectWork}
				isWorkSelected={isWorkSelected}
				columns={columns}
				renderCell={renderCell}
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
