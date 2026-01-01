import styles from './styles.module.scss';
import {createPortal} from 'react-dom'
import {ReactNode, useEffect} from 'react'

type Props = {
    close: () => void,
    children: ReactNode,
}
export default function Modal(props: Props)
{
    const m = document.getElementById('modal');
    if (!m )
    {
        console.error('Element with ID modal not found!!!');
        return null;
    }

    const fn = (e: PointerEvent) => { e.target == m && props.close() };


    useEffect(() => {
        m.addEventListener('click', fn);
    }, [])

    useEffect(() => {
        return () =>
        {
            props.close();
            m?.removeEventListener('click', fn);
        }
    })

    return createPortal(
        <div className={styles.modal}>
            {props.children}
        </div>, m
    );
}
