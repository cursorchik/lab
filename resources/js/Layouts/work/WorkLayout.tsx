import {Head, Link} from '@inertiajs/react';
import React from 'react';
import styles from './main.module.scss';
import SubLink from "@/Components/custom/sublink/SubLink";

interface WorkLayoutProps {
    title: string;
    flash?: { success: string; error: string };
    children?: React.ReactNode;
}

export default function WorkLayout({title, flash, children}: WorkLayoutProps)
{
    return (<>
        <Head title={title}></Head>
        <div className={styles.layout}>
            <div className={styles.header}></div>
            <div className={styles.sidebar}>
                <Link href="/">Главная</Link>
                <Link href="/clinics">Список клиник</Link>
                <Link href="/mechanics">Список техников</Link>
                <Link href="/works_types">Список видов работ</Link>
            </div>
            <div className={styles.content}>
                <h4>{title}</h4>
                {flash?.success && (<div className="alert alert-success mt-3">{flash.success}</div>)}
                {flash?.error && (<div className="alert alert-danger mt-3">{flash.error}</div>)}
                {children}
            </div>
        </div>
    </>);
}
