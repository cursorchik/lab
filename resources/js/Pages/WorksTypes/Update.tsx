import {Form, Link, usePage} from "@inertiajs/react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import React from "react";
import ErrorHint from '@/Components/custom/Error'

type WorksTypesProps = {
    prev_url: string
    item: { id: number, name: string, cost: number }
}

export default function Update(props: WorksTypesProps)
{
    const { errors } = usePage().props;

    return (<WorkLayout title="Список типов работ / Изменение ">
        <Link href="/works_types" className="btn btn-link">Назад</Link>
        <Link onClick={(e) => { if (!confirm('Удалить тип работы?')) { e.preventDefault(); } } } className="btn btn-link" title="Удалить" href={'/works_types/destroy/' + props.item.id}>Удалить</Link>
        <Form className="mt-4" action={ '/works_types/update/' + props.item.id } method="post">
            <div className="flex flex-col justify-center min-w-60 max-w-screen-md">
                <div className="mb-3">
                    <label htmlFor="name" className="form-label">Название</label>
                    <textarea className="form-control" name="name" id="name" defaultValue={props.item.name}/>
					<ErrorHint text={errors.name}/>
                </div>
                <div className="mb-3">
                    <label htmlFor="cost" className="form-label">Стоимость</label>
                    <input type="number" defaultValue={props.item.cost} className="form-control" id="cost" name="cost"/>
					<ErrorHint text={errors.cost}/>
                </div>

                <button className="btn btn-primary" type="submit">Применить</button>
            </div>
        </Form>
    </WorkLayout>);
}
