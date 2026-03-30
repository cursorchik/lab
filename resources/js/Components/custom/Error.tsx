import React from 'react'

export default function ErrorHint(props: {text: string})
{
	return (props.text ? (<div className="inline-block text-xs p-2 bg-gray-300 font-medium text-gray-700 mt-2 rounded-sm">⛔ {props.text}</div>) : null);
}
