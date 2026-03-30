import React, { useState, useEffect, useMemo, useRef, useCallback } from 'react';
import Select, {
	components,
	MultiValueProps,
	OptionProps,
	MultiValue,
	SingleValue,
	ActionMeta,
} from 'react-select';

export type OptionType = { id: number; name: string };
export type SelectedItem = { id: number; quantity: number };

type MultiSelectQuantityProps = {
	options: OptionType[];
	value: SelectedItem[];
	onChange: (newValue: SelectedItem[]) => void;
	placeholder?: string;
};

const CustomOption: React.FC<OptionProps<OptionType, true>> = (props) => {
	const { data, isSelected, innerRef, innerProps } = props;
	return (
		<div
			ref={innerRef}
			{...innerProps}
			className={`flex items-center px-3 py-2 cursor-pointer ${
				isSelected ? 'bg-blue-100' : 'hover:bg-gray-50'
			}`}
		>
			<input
				type="checkbox"
				checked={isSelected}
				readOnly
				className="mr-2 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
			/>
			<span>{data.name}</span>
		</div>
	);
};

const CustomMultiValue = React.memo((props: MultiValueProps<OptionType> & { selectedItemsRef: React.MutableRefObject<SelectedItem[]>, onQuantityChangeRef: React.MutableRefObject<(id: number, qty: number) => void> }) =>
{
	const { data, innerProps, removeProps, selectedItemsRef, onQuantityChangeRef } = props;
	const selectedItem = selectedItemsRef.current.find((item) => item.id === data.id);
	const externalQuantity = selectedItem?.quantity ?? 1;

	const [localQuantity, setLocalQuantity] = useState(externalQuantity);

	useEffect(() => { setLocalQuantity(externalQuantity); }, [externalQuantity]);

	const updateQuantity = useCallback((newQuantity: number) =>
	{
		const clamped = Math.max(1, newQuantity);
		setLocalQuantity(clamped);
		onQuantityChangeRef.current(data.id, clamped);
	}, [data.id, onQuantityChangeRef]);


	const handleKeyDown = (e: React.KeyboardEvent<HTMLInputElement>) =>
	{
		e.stopPropagation();
		if (e.key === 'ArrowUp') { e.preventDefault(); updateQuantity(localQuantity + 1); }
		else if (e.key === 'ArrowDown') { e.preventDefault(); updateQuantity(localQuantity - 1); }
	};

	return (
		<div{...innerProps} className="inline-flex items-center bg-blue-100 rounded-md px-2 py-1 mr-1 mb-1">
			<span className="mr-1">{data.name}</span>
			<input
				type="number"
				min="1"
				value={localQuantity}
				onChange={(e: React.ChangeEvent<HTMLInputElement>) => updateQuantity(parseInt(e.target.value) || 1)}
				onKeyDown={handleKeyDown}
				onClick={(e) => e.stopPropagation()}
				className="w-12 px-1 py-0 text-sm border border-blue-300 rounded focus:outline-none focus:ring-1 focus:ring-blue-500"
			/>
			<span {...removeProps} className="ml-1 cursor-pointer text-blue-700 hover:text-blue-900">×</span>
		</div>
	);
});

export const MultiSelectQuantity: React.FC<MultiSelectQuantityProps> = ({ options, value, onChange, placeholder = 'Выберите элементы...' }) =>
{
	const selectedIds = value.map((item) => item.id);
	const selectedOptions = options.filter((opt) => selectedIds.includes(opt.id));

	const selectedItemsRef = useRef(value);

	// Инициализируем Ref функцией-заглушкой, чтобы TypeScript не ругался
	const onQuantityChangeRef = useRef<(id: number, qty: number) => void>(() => {});

	// Обновляем Ref при изменении value или onChange
	useEffect(() => { selectedItemsRef.current = value; }, [value]);

	useEffect(() =>
	{
		onQuantityChangeRef.current = (id: number, newQuantity: number) =>
		{
			const newValue = selectedItemsRef.current.map((item) =>
				item.id === id ? { ...item, quantity: newQuantity } : item
			);
			onChange(newValue);
		};
	}, [onChange]); // зависимость только от onChange, selectedItemsRef.current всегда актуален

	const handleSelectChange = (newValue: MultiValue<OptionType> | SingleValue<OptionType>, actionMeta: ActionMeta<OptionType>) =>
	{
		const selected = (newValue as MultiValue<OptionType>) || [];
		const newValueItems: SelectedItem[] = selected.map((opt) =>
		{
			const existing = selectedItemsRef.current.find((item) => item.id === opt.id);
			return existing ? existing : { id: opt.id, quantity: 1 };
		});
		onChange(newValueItems);
	};

	const optionComponent = useMemo(() => CustomOption, []);
	const MultiValueComponent = useCallback(
		(props: MultiValueProps<OptionType>) => (
			<CustomMultiValue
				{...props}
				selectedItemsRef={selectedItemsRef}
				onQuantityChangeRef={onQuantityChangeRef}
			/>
		),
		[]
	);

	return (
		<Select
			isMulti
			options={options}
			value={selectedOptions}
			onChange={handleSelectChange}
			placeholder={placeholder}
			getOptionLabel={(opt) => opt.name}
			getOptionValue={(opt) => String(opt.id)}
			closeMenuOnSelect={false}
			hideSelectedOptions={false}
			isClearable={false}
			components={{
				Option: optionComponent,
				MultiValue: MultiValueComponent,
			}}
			styles={{
				control: (base) => ({
					...base,
					backgroundColor: 'white',
					borderColor: '#d1d5db',
					'&:hover': { borderColor: '#9ca3af' },
					boxShadow: 'none',
				}),
				menu: (base) => ({
					...base,
					borderRadius: '0.375rem',
					boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)',
				}),
				option: (base, state) => ({
					...base,
					backgroundColor: state.isSelected ? '#eff6ff' : 'white',
					'&:hover': { backgroundColor: '#f9fafb' },
					cursor: 'pointer',
				}),
				multiValue: (base) => ({
					...base,
					backgroundColor: 'transparent',
				}),
				valueContainer: (base) => ({
					...base,
					display: 'flex',
					flexWrap: 'wrap',
					gap: '4px',
				}),
			}}
		/>
	);
};
