import {Form, Link, usePage} from "@inertiajs/react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import React from "react";
import ErrorHint from '@/Components/custom/Error'

type MechanicsProps = {
    prev_url: string
}

export default function Create(props: MechanicsProps)
{
	let { errors, flash } = usePage().props as any;

    return (<WorkLayout title="Список техников / Создание" flash={flash}>
        <Link href="/mechanics" className="btn btn-link">Назад</Link>
        <Form className="mt-4" action="/mechanics/store" method="post">
            <div className="flex flex-col justify-center min-w-60 max-w-screen-md">
                <div className="mb-3">
                    <label htmlFor="name" className="form-label">Имя</label>
                    <textarea className="form-control" name="name" id="name" aria-describedby="nameHelp"/>
					<ErrorHint text={errors.name}/>
                </div>

                <button className="btn btn-primary" type="submit">Добавить</button>
            </div>
        </Form>
    </WorkLayout>);
}
