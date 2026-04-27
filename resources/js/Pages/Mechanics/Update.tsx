import {Form, Link, usePage} from "@inertiajs/react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import React from "react";
import ErrorHint from '@/Components/custom/Error'

type MechanicsProps = {
    prev_url: string
    item: { id: number; name: string }
}

export default function Update(props: MechanicsProps)
{
	let { errors, flash } = usePage().props as any;

    return (<WorkLayout title="Список техников / Изменение"  flash={flash}>
        <Link href="/mechanics" className="btn btn-link">Назад</Link>
        <Link onClick={(e) => { if (!confirm('Удалить механика?')) { e.preventDefault(); } } } className="btn btn-link" title="Удалить" href={'/mechanics/destroy/' + props.item.id}>Удалить</Link>
        <Form className="mt-4" action={ '/mechanics/update/' + props.item.id } method="post">
            <div className="flex flex-col justify-center min-w-60 max-w-screen-md">
                <div className="mb-3">
                    <label htmlFor="name" className="form-label">Название</label>
                    <textarea className="form-control" name="name" id="name" defaultValue={props.item.name}/>
					<ErrorHint text={errors.name}/>
                </div>

                <button className="btn btn-primary" type="submit">Применить</button>
            </div>
        </Form>
    </WorkLayout>);
}
