import {Form, Link, usePage} from "@inertiajs/react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import React from "react";

type WorksTypesProps = {
    prev_url: string,
    clinics: {id: number, name: string }[],
    mechanics: {id: number, name: string }[],
    works_types: {id: number, name: string, cost_clinic: number, cost_mechanic: number }[],
}

export default function Create(props: WorksTypesProps)
{
	let { errors, flash } = usePage().props as any;

    return (<WorkLayout title="Список типов работ / Создание"  flash={flash}>
        <Link href="/works_types" className="btn btn-link">Назад</Link>
        {/*<Link href="/works_types/import_preview">Импорт</Link>*/}
        <Form className="mt-4" action="/works_types/store" method="post">
            <div className="flex flex-col justify-center min-w-60 max-w-screen-md">
                <div className="mb-3">
                    <label htmlFor="name" className="form-label">Название</label>
                    <textarea className="form-control" name="name" id="name" aria-describedby="nameHelp"/>
                    {errors.name && (<div className="inline-block text-xs p-2 bg-gray-300 font-medium text-gray-700 mt-2 rounded-sm">⛔ {errors.name}</div>)}
                </div>
                <div className="mb-3">
                    <label htmlFor="cost_clinic" className="form-label">Стоимость для клиники</label>
                    <input type="number" className="form-control" name="cost_clinic" id="cost_clinic" aria-describedby="cost_clinicHelp"/>
                    {errors.cost_clinic && (<div className="inline-block text-xs p-2 bg-gray-300 font-medium text-gray-700 mt-2 rounded-sm">⛔ {errors.cost_clinic}</div>)}
                </div>
                <div className="mb-3">
                    <label htmlFor="cost_mechanic" className="form-label">Стоимость для техника</label>
                    <input type="number" className="form-control" name="cost_mechanic" id="cost_mechanic" aria-describedby="cost_mechanicHelp"/>
                    {errors.cost_mechanic && (<div className="inline-block text-xs p-2 bg-gray-300 font-medium text-gray-700 mt-2 rounded-sm">⛔ {errors.cost_mechanic}</div>)}
                </div>

                <button className="btn btn-primary" type="submit">Добавить работу</button>
            </div>
        </Form>
    </WorkLayout>);
}
