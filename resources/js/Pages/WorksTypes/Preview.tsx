// import {Form, Link, usePage} from "@inertiajs/react";
// import WorkLayout from "@/Layouts/work/WorkLayout";
// import {useState} from "react";
//
// type WorksTypesProps = {
//     prev_url: string
// }
//
// export default function Preview(props: WorksTypesProps)
// {
//     const { errors } = usePage().props;
//
//     const [value, setValue] = useState(true);
//
//     const items: {name: string, cost: number}[] = [];
//     const textarea = <textarea id="raw" className="form-control mb-3" defaultValue="" onInput={ (e) =>
//         {
//             console.log(e);
//             const value: string = e.target.value.trim();
//             const rows: string[] = value.split('\n');
//             for (const row of rows)
//             {
//                 const item = row.split('\t');
//                 items.push({name: item[0].trim(), cost: Number(item[1])})
//             }
//             setValue(setValue.length > 0);
//         }
//     }/>;
//
//     return (<WorkLayout title="Список типов работ / Создание">
//         { props.prev_url && <Link href={props.prev_url ?? '/'}>Назад</Link> }
//         <Form className="mt-4" action="/works_types/import" method="post">
//             <div className="flex flex-col justify-center min-w-60 max-w-screen-md">
//                 <div>
//                     <label htmlFor="raw" className="form-label">Название</label>
//                     {textarea}
//                     {errors.name && (<div>{errors.name}</div>)}
//                 </div>
//
//                 {value && items.map((item, i) => (<div key={i}>
//                     <div className="mb-3">
//                         <label htmlFor="name" className="form-label">Название</label>
//                         <textarea className="form-control" id="name" aria-describedby="nameHelp" defaultValue={item.name}/>
//                     </div>
//                     <div className="mb-3">
//                         <label htmlFor="cost" className="form-label">Цена</label>
//                         <input type="number" className="form-control" value={item.cost} id="cost" aria-describedby="costHelp"/>
//                     </div>
//                 </div>))}
//                 <button className="btn btn-primary" type="submit">Импортировать</button>
//             </div>
//         </Form>
//     </WorkLayout>);
// }

import {Form, Link, usePage} from "@inertiajs/react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import React, {useEffect, useState} from "react";

type WorksTypesProps = {
    prev_url: string
}

type WorkItem = {
    name: string;
    cost_clinic: number;
    cost_mechanic: number;
}

export default function Preview(props: WorksTypesProps)
{
    let { errors, flash } = usePage().props as any;

    const [items, setItems] = useState<WorkItem[]>([]);
    const [localFlash, setLocalFlash] = useState(flash);

    // Синхронизируем локальное состояние с props при монтировании
    useEffect(() => {
        setLocalFlash(flash);
    }, [flash]);

    const handleTextareaChange = (e: React.ChangeEvent<HTMLTextAreaElement>) =>
    {
        setLocalFlash({});

        const value: string = e.target.value.trim();
        const rows: string[] = value.split('\n');
        const parsedItems: WorkItem[] = [];

        for (const row of rows)
        {
            if (row.trim() === '') continue;

            const item = row.split('\t');
            if (item.length >= 3) {
                parsedItems.push({
                    name: item[0].trim(),
                    cost_clinic: Number(item[1]),
					cost_mechanic: Number(item[2]),
                });
            }
        }

        setItems(parsedItems);
    };

    const mapErr: Map<number, Map<string, string>> = new Map();

    for (const key in errors)
    {
        const keySplitted = key.split('.'); // 0: form, 1: index, 2: field
        const k = Number(keySplitted[1]);
        if (mapErr.has(k))
        {
            const map = mapErr.get(k);
            map?.set(keySplitted[2], errors[key]);
        }
        else
        {
            const map = new Map<string, string>();
            map.set(keySplitted[2], errors[key])
            mapErr.set(k, map);
        }
    }

    return (<WorkLayout title="Список типов работ / Создание" flash={localFlash}>
        <Link href="/works_types/create" className="btn btn-link">Назад</Link>
        <Form className="mt-4" action="/works_types/import" method="post">
            {errors.items && (<div>{errors.items}</div>)}
            <div className="flex flex-col justify-center min-w-60 max-w-screen-md">
                <div>
                    <label htmlFor="raw" className="form-label">Введите данные (название и стоимость для клиники и стоимость для техника):</label>
                    <textarea id="raw" className="form-control mb-3" placeholder="Коронка 2000 1500 &#10;Протез 5000 2500 &#10;..." rows={5} onChange={handleTextareaChange}/>
                </div>

                {items.length > 0 && (
                    <div className="mt-4">
                        <h3>Предпросмотр:</h3>
                        {
                            items.map((item, i) =>
                            {
                                const ename = mapErr.get(i)?.get('name');
                                const error_cost_clinic = mapErr.get(i)?.get('cost_clinic');
                                const error_cost_mechanic = mapErr.get(i)?.get('cost_mechanic');

                                return <div key={i} className="border p-3 mb-3">
                                    <div className="mb-3">
                                        <label htmlFor={`name-${i}`} className="form-label">Название</label>
                                        <input className="form-control" name={'form[' + i + '][name]'} value={item.name} id={`name-${i}`} readOnly/>
                                        {ename && (<div>{ename}</div>)}
                                    </div>
                                    <div className="mb-3">
                                        <label htmlFor={`cost_clinic-${i}`} className="form-label">Стоимость для клиники</label>
                                        <input className="form-control" name={'form[' + i + '][cost_clinic]'} value={item.cost_clinic} id={`cost_clinic-${i}`} type="number" readOnly/>
                                        {error_cost_clinic && (<div>{error_cost_clinic}</div>)}
                                    </div>
                                    <div className="mb-3">
                                        <label htmlFor={`cost_mechanic-${i}`} className="form-label">Стоимость для техника</label>
                                        <input className="form-control" name={'form[' + i + '][cost_mechanic]'} value={item.cost_mechanic} id={`cost_mechanic-${i}`} type="number" readOnly/>
                                        {error_cost_mechanic && (<div>{error_cost_mechanic}</div>)}
                                    </div>
                                </div>;
                            })
                        }
                    </div>
                )}
                <button className="btn btn-primary sticky bottom-0" type="submit">Импортировать</button>
            </div>
        </Form>
    </WorkLayout>);
}
