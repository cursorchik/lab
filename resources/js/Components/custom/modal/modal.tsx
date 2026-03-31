import styles from './styles.module.scss';
import { createPortal } from 'react-dom';
import React, { ReactNode, useEffect, useRef } from 'react';

type Props = {
	close: () => void;
	children: ReactNode;
};

export default function Modal(props: Props)
{
	const m = document.getElementById('modal');
	const contentRef = useRef<HTMLDivElement>(null);

	if (!m) { console.error('Element with ID modal not found!!!'); return null; }

	const handleBackdropClick = (e: MouseEvent) => { if (e.target === m) props.close(); };

	useEffect(() =>
	{
		m.addEventListener('click', handleBackdropClick);
		return () => m.removeEventListener('click', handleBackdropClick);
	}, [m]);

	const handleContentClick = (e: React.MouseEvent) => e.stopPropagation();

	return createPortal(
		<div className={styles.modal} ref={contentRef} onClick={handleContentClick}>
			{props.children}
		</div>,
		m
	);
}
