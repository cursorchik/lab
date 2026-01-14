import {Form, Link, usePage} from "@inertiajs/react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import React from "react";

type ClinicsProps = {
    prev_url: string
    item: { id: number; name: string }
}

export default function Update(props: ClinicsProps)
{
    const { errors } = usePage().props;

    return (<WorkLayout title="Список клиник / Изменение ">
        { props.prev_url && <Link href="/clinics">Назад</Link> }
        <Link onClick={(e) => { if (!confirm('Удалить клинику?')) { e.preventDefault(); } } } className="btn btn-link" title="Удалить" href={'/clinics/destroy/' + props.item.id}>Удалить</Link>
        <Form className="mt-4" action={ '/clinics/update/' + props.item.id } method="post">
            <div className="flex flex-col justify-center min-w-60 max-w-screen-md">
                <div className="mb-3">
                    <label htmlFor="name" className="form-label">Название</label>
                    <textarea className="form-control" name="name" id="name" defaultValue={props.item.name}/>
                    {errors.name && (<div>{errors.name}</div>)}
                </div>

                <button className="btn btn-primary" type="submit">Применить</button>
            </div>
        </Form>
    </WorkLayout>);
}
