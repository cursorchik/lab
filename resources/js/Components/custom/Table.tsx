import {Head, Link} from '@inertiajs/react';
import WorkLayout from "@/Layouts/work/WorkLayout";
import React from "react";
export default function TableBrowse(props: { urls: { prev?: string, add: string }, title: string, children: React.ReactNode })
{
    return (<WorkLayout title={props.title}>
        { props.urls?.prev && <Link className="btn btn-link" href={props.urls.prev}>Назад</Link> }
        <Link className="btn btn-link" href={props.urls.add}>Добавить</Link>
        {props.children}
    </WorkLayout>);
}
