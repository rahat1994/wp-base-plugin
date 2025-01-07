import { h } from "vue";

export const columns = [
    {
        accessorKey: "title",
        header: () => h("div", { class: "text-left" }, "Title"),
        cell: ({ row }) => {
            return h("div", { class: "text-left font-medium" }, [
                h("span", row.getValue("title")),
                h("div", { class: "actions space-x-2" }, [
                    h(
                        "a",
                        {
                            href: "#",
                            class: "edit-link text-sm font-thin text-blue-500",
                        },
                        "Edit"
                    ),
                    h(
                        "a",
                        {
                            href: "#",
                            class: "delete-link text-sm font-thin text-red-500 border-l-2 border-gray-300 pl-2",
                        },
                        "Delete"
                    ),
                    h(
                        "a",
                        {
                            href: "#",
                            class: "view-link text-sm font-thin text-blue-500 border-l-2 border-gray-300 pl-1",
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
