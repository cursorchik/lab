import {Link, router} from "@inertiajs/react";
import { useEffect } from "react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import { useAccounting } from "@/hooks/useAccounting";
import { AccountingTable } from "@/Components/custom/AccountingTable";

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
	prev_url?: string;
	id: number;
	url?: string;
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

		router.post("/mechanics/lock-works", {
			mechanic_id: props.id,
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

	const columns = [
		{ key: 'checkbox', header: '', className: 'no-print' },
		{ key: 'patient', header: 'Ф.И.О пациента' },
		{ key: 'start', header: 'Дата' },
		{ key: 'name', header: 'Изделие' },
		{ key: 'cost', header: 'Цена за ед.' },
		{ key: 'count', header: 'Кол-во ед.' },
		{ key: 'salary', header: 'Итого' },
	];

	return (
		<WorkLayout title={`Зарплатная ведомость - ${props.name}`}>
			<div className="d-flex justify-content-between no-print">
				<Link href="/mechanics" method="post" className="btn btn-link">Назад</Link>
				<button className="btn btn-primary" onClick={handleLockAndPrint}>
					Заблокировать выбранные и распечатать
				</button>
				<a className="btn btn-link" onClick={() => window.print()}>
					Распечатать без блокировки
				</a>
			</div>

			<h6>Зарплатная ведомость за выбранный месяц – Техник {props.name}</h6>

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
