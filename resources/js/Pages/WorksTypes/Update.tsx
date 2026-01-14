import {Form, Link, usePage} from "@inertiajs/react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import React from "react";

type WorksTypesProps = {
    prev_url: string
    item: { id: number, name: string, cost: number }
}

export default function Update(props: WorksTypesProps)
{
    const { errors } = usePage().props;

    return (<WorkLayout title="Список типов работ / Изменение ">
        { props.prev_url && <Link href={props.prev_url ?? '/'}>Назад</Link> }
        <Form className="mt-4" action={ '/works_types/update/' + props.item.id } method="post">
            <div className="flex flex-col justify-center min-w-60 max-w-screen-md">
                <div className="mb-3">
                    <label htmlFor="name" className="form-label">Название</label>
                    <textarea className="form-control" name="name" id="name" defaultValue={props.item.name}/>
                    {errors.name && (<div>{errors.name}</div>)}
                </div>
                <div className="mb-3">
                    <label htmlFor="cost" className="form-label">Цена</label>
                    <input type="number" defaultValue={props.item.cost} className="form-control" id="cost" name="cost"/>
                    {errors.cost && (<div>{errors.cost}</div>)}
                </div>

                <button className="btn btn-primary" type="submit">Применить</button>
            </div>
        </Form>
    </WorkLayout>);
}
