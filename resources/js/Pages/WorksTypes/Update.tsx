import {Form, Link, usePage} from "@inertiajs/react";
import WorkLayout from "@/Layouts/work/WorkLayout";
import React from "react";
import ErrorHint from '@/Components/custom/Error'

type WorksTypesProps = {
    prev_url: string
    item: { id: number, name: string, cost_clinic: number, cost_mechanic: number }
}

export default function Update(props: WorksTypesProps)
{
	let { errors, flash } = usePage().props as any;

    return (<WorkLayout title="Список типов работ / Изменение" flash={flash}>
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
                    <label htmlFor="cost_clinic" className="form-label">Стоимость для клиники</label>
                    <input type="number" defaultValue={props.item.cost_clinic} className="form-control" id="cost_clinic" name="cost_clinic"/>
					<ErrorHint text={errors.cost_clinic}/>
                </div>
                <div className="mb-3">
                    <label htmlFor="cost_mechanic" className="form-label">Стоимость для техника</label>
                    <input type="number" defaultValue={props.item.cost_mechanic} className="form-control" id="cost_mechanic" name="cost_mechanic"/>
					<ErrorHint text={errors.cost_mechanic}/>
                </div>

                <button className="btn btn-primary" type="submit">Применить</button>
            </div>
        </Form>
    </WorkLayout>);
}
