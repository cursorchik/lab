import WorkLayout from "@/Layouts/work/WorkLayout";

// Итоговый счет на оплату

type Props = {
    prev_url: string,
    clinic: {
        id: number,
        name: string,
        final_payment_invoice: number,
        items: {
            id          : number
            start       : string
            count       : number
            patient     : string
            work_type   : { id: number, name: string, cost: number }
        }[]
    },
    mechanic: {
        id: number,
        name: string,
        final_payment_invoice: number,
        items: {
            id          : number,
            name        : string,
            cost        : number,
            count       : number,
            patient     : string
            work_type   : { id: number, name: string, cost: number }
        }[]
    }
}
export default function Browse(props: Props)
{
    console.log(props);
    return (<WorkLayout title="Бухгалтерия">

    </WorkLayout>);
}
