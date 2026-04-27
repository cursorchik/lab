import {Form, Link, usePage} from "@inertiajs/react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import React from "react";
import ErrorHint from '@/Components/custom/Error'

type ClinicsProps = {
    prev_url: string
}

export default function Create(props: ClinicsProps)
{
	let { errors, flash } = usePage().props as any;

    return (<WorkLayout title="Список клиник / Создание" flash={flash}>
        <Link href="/clinics" className="btn btn-link">Назад</Link>
        <Form className="mt-4" action="/clinics/store" method="post">
            <div className="flex flex-col justify-center min-w-60 max-w-screen-md">
                <div className="mb-3">
                    <label htmlFor="name" className="form-label">Название</label>
                    <textarea className="form-control" name="name" id="name" aria-describedby="nameHelp"/>
					<ErrorHint text={errors.name}/>
                </div>

                <button className="btn btn-primary" type="submit">Добавить</button>
            </div>
        </Form>
    </WorkLayout>);
}
