import styles from './styles.module.scss';
import {ReactNode, useEffect, useState} from 'react'

type Props = {
    title: string,
    children: ReactNode[],
}
export default function SubLink(props: Props)
{
    const [showSub, setShowSub] = useState(false);

    return <ul className={"btn btn-link " + styles.sub_link} onClick={ () => setShowSub(!showSub) }>
        {props.title}
        { showSub && <ul>{ props.children.map((child: ReactNode, index: number) => <li key={index} className="btn btn-link" onClick={ (e) => e.stopPropagation() }>{child}</li>) }</ul>}
    </ul>;
}
