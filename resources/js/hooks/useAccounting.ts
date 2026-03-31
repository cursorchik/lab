// hooks/useAccounting.ts
import { useState } from "react";

interface WorkDetail {
	id: number;
	start: string;
	patient: string;
	name: string;
	cost: number;
	count: number;
	salary: number;
}

export const useAccounting = (items: Record<number, WorkDetail[]>) =>
{
	const [selectedIds, setSelectedIds] = useState<number[]>([]);

	const detailedItems = Object.values(items).flat();
	const workIds = Object.keys(items).map(Number);
	const allSelected = workIds.length > 0 && selectedIds.length === workIds.length;

	// Общие итоги (все работы)
	const totalCountAll = detailedItems.reduce((sum, item) => sum + item.count, 0);
	const totalAmountAll = detailedItems.reduce((sum, item) => sum + item.salary, 0);

	// Итоги по выбранным работам
	const selectedTotalCount = Object.keys(items)
		.filter(id => selectedIds.includes(Number(id)))
		.reduce((sum, workId) => {
			const workDetails = items[Number(workId)];
			return sum + workDetails.reduce((s, d) => s + d.count, 0);
		}, 0);

	const selectedTotalAmount = Object.keys(items)
		.filter(id => selectedIds.includes(Number(id)))
		.reduce((sum, workId) => {
			const workDetails = items[Number(workId)];
			return sum + workDetails.reduce((s, d) => s + d.salary, 0);
		}, 0);

	// Итоги для отображения (если есть выбор – по выбранным, иначе общие)
	const totalCount = selectedIds.length > 0 ? selectedTotalCount : totalCountAll;
	const totalAmount = selectedIds.length > 0 ? selectedTotalAmount : totalAmountAll;

	const handleSelectAll = () => {
		if (allSelected) {
			setSelectedIds([]);
		} else {
			setSelectedIds(workIds);
		}
	};

	const handleSelectWork = (workId: number) =>
	{
		setSelectedIds(prev =>
			prev.includes(workId) ? prev.filter(id => id !== workId) : [...prev, workId]
		);
	};

	const isWorkSelected = (workId: number) => selectedIds.includes(workId);

	return {
		selectedIds,
		detailedItems,
		workIds,
		allSelected,
		totalCount,
		totalAmount,
		handleSelectAll,
		handleSelectWork,
		isWorkSelected,
	};
};
