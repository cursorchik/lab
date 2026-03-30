import {Form, Link, usePage} from "@inertiajs/react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import React from "react";

type WorksTypesProps = {
    prev_url: string,
    clinics: {id: number, name: string }[],
    mechanics: {id: number, name: string }[],
    works_types: {id: number, name: string, cost: number }[],
}

export default function Create(props: WorksTypesProps)
{
    const { errors } = usePage().props;

    return (<WorkLayout title="Список типов работ / Создание">
        <Link href="/works_types" className="btn btn-link">Назад</Link>
        <Link href="/works_types/import_preview">Импорт</Link>
        <Form className="mt-4" action="/works_types/store" method="post">
            <div className="flex flex-col justify-center min-w-60 max-w-screen-md">
                <div className="mb-3">
                    <label htmlFor="name" className="form-label">Название</label>
                    <textarea className="form-control" name="name" id="name" aria-describedby="nameHelp"/>
                    {errors.name && (<div className="inline-block text-xs p-2 bg-gray-300 font-medium text-gray-700 mt-2 rounded-sm">⛔ {errors.name}</div>)}
                </div>
                <div className="mb-3">
                    <label htmlFor="cost" className="form-label">Стоимость</label>
                    <input type="number" className="form-control" name="cost" id="cost" aria-describedby="costHelp"/>
                    {errors.cost && (<div className="inline-block text-xs p-2 bg-gray-300 font-medium text-gray-700 mt-2 rounded-sm">⛔ {errors.cost}</div>)}
                </div>

                <button className="btn btn-primary" type="submit">Добавить работу</button>
            </div>
        </Form>
    </WorkLayout>);
}
