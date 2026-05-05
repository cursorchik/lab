import React from "react";

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

export interface ColumnConfig
{
	key: string;
	header: string;
	className?: string;
}

interface AccountingTableProps
{
	items: Record<number, WorkDetail[]>;
	allSelected: boolean;
	totalCount: number;
	totalAmount: number;
	handleSelectAll: () => void;
	handleSelectWork: (workId: number) => void;
	isWorkSelected: (workId: number) => boolean;
	columns: ColumnConfig[];
	renderCell?: (item: WorkDetail, column: ColumnConfig, isFirstInWork: boolean, totalSalary: number) => React.ReactNode; // Функция для особого рендеринга ячеек
}

export const AccountingTable: React.FC<AccountingTableProps> = ({items, allSelected, totalCount, totalAmount, handleSelectAll, handleSelectWork, isWorkSelected, columns, renderCell: customRenderCell}) =>
{
	const detailedItems = Object.values(items).flat();

	const formatMoney = (amount: number) : string => amount.toLocaleString('ru-RU');

	const defaultRenderCell = (item: WorkDetail, column: ColumnConfig, isFirstInWork: boolean, totalSalary: number) =>
	{
		switch (column.key)
		{
			case 'checkbox'	: return isFirstInWork ? (<input type="checkbox" checked={isWorkSelected(item.id)} onChange={() => handleSelectWork(item.id)}/>) : null;
			case 'start'	: return isFirstInWork ? item.start : '';
			case 'patient'	: return isFirstInWork ? item.patient : '';
			case 'name'		: return item.name;
			case 'cost'		: return formatMoney(item.cost);
			case 'count'	: return item.count;
			case 'salary'	: return formatMoney(item.salary);
			default         : return '';
		}
	};

	const renderCell = customRenderCell || defaultRenderCell;

	return (
		<table className="table table-hover text-sm mt-4">
			<thead>
				<tr>{columns.map(col => (<th key={col.key} className={col.className || ''}>{col.header}</th>))}</tr>
			</thead>
			<tbody>
				{detailedItems.length > 0 ? (
					detailedItems.map((item, index) =>
					{
						const isFirstInWork = index === 0 || detailedItems[index - 1]?.id !== item.id;
						const workDetails = items[item.id];
						const totalSalary = workDetails?.reduce((sum, d) => sum + d.salary, 0) || 0;
						const isSelected = isWorkSelected(item.id);
						const rowClass = !isSelected ? 'row-not-selected' : '';

						return (<tr key={`${item.id}-${index}`} className={rowClass}>
							{
								columns.map(col => (
									<td key={col.key} className={col.className || ''}>
										{renderCell(item, col, isFirstInWork, totalSalary)}
									</td>)
								)
							}
						</tr>);
					})
				)
					: (<tr><td colSpan={columns.length} className="text-center">Данные отсутствуют</td></tr>)
				}
			</tbody>
			{detailedItems.length > 0 && (
				<tfoot>
					<tr style={{fontWeight: 'bold', borderTop: '2px solid #dee2e6'}}>
						<td colSpan={columns.length - 2} className="text-end">Итого:</td>
						<td>{totalCount}</td>
						<td>{formatMoney(totalAmount)}</td>
					</tr>
				</tfoot>
			)}
		</table>
	);
};
