import {Form, Link, usePage} from "@inertiajs/react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import React from "react";

type ClinicsProps = {
    prev_url: string
}

export default function Create(props: ClinicsProps)
{
    const { errors } = usePage().props;

    return (<WorkLayout title="Список клиник / Создание">
        <Link href="/clinics" className="btn btn-link">Назад</Link>
        <Form className="mt-4" action="/clinics/store" method="post">
            <div className="flex flex-col justify-center min-w-60 max-w-screen-md">
                <div className="mb-3">
                    <label htmlFor="name" className="form-label">Название</label>
                    <textarea className="form-control" name="name" id="name" aria-describedby="nameHelp"/>
                    {errors.name && (<div>{errors.name}</div>)}
                </div>

                <button className="btn btn-primary" type="submit">Добавить</button>
            </div>
        </Form>
    </WorkLayout>);
}
