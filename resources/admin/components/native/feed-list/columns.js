import { h } from "vue";
import { Separator } from "@/components/ui/separator";
import ActionLink from "./ActionLink.vue";

export const columns = [
    {
        accessorKey: "title",
        header: () => h("div", { class: "text-left" }, "Title"),
        cell: ({ row }) => {
            return h("div", { class: "text-left font-medium" }, [
                h("span", row.getValue("title")),
                h("div", { class: "actions space-x-2" }, [
                    h(
                        ActionLink,
                        {
                            to: "/settings",
                            label: "Edit",
                        },
                        "Edit"
                    ),
                    h(
                        ActionLink,
                        {
                            to: "/settings",
                            label: "Delete",
                            color: "red-500",
                            hasSeparator: true,
                        },
                        "Delete"
                    ),
                    h(
                        ActionLink,
                        {
                            to: "/settings",
                            hasSeparator: true,
                        },
                        "View"
                    ),
                ]),
            ]);
        },
    },
    {
        accessorKey: "author",
        header: () => h("div", { class: "text-left" }, "Email"),
        cell: ({ row }) => {
            return h(
                "div",
                { class: "text-left font-medium" },
                row.getValue("author")
            );
        },
    },
    {
        accessorKey: "amount",
        header: () => h("div", { class: "text-left" }, "Amount"),
        cell: ({ row }) => {
            const amount = Number.parseFloat(row.getValue("amount"));
            const formatted = new Intl.NumberFormat("en-US", {
                style: "currency",
                currency: "USD",
            }).format(amount);

            return h("div", { class: "text-left font-medium" }, formatted);
        },
    },
];
