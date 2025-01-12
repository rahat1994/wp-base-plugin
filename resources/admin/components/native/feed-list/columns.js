import { h } from "vue";
import ActionLink from "./ActionLink.vue";
import DropdownAction from "@/components/native/feed-list/DataTableDropDown.vue";
import { RouterLink } from "vue-router";

export const columns = [
    {
        accessorKey: "title",
        header: () => h("div", { class: "text-left" }, "Title"),
        cell: ({ row }) => {
            return h("div", { class: "text-left font-medium" }, [
                h(
                    RouterLink,
                    { to: "/editor/" + row.getValue("id"), class: "underline" },
                    row.getValue("title")
                ),
                h("div", { class: "actions space-x-2 mt-1" }, [
                    h(
                        ActionLink,
                        {
                            to: "/settings",
                            label: "Edit",
                            type: "edit",
                            data: row.original,
                        },
                        "Edit"
                    ),
                    h(
                        ActionLink,
                        {
                            to: "/settings",
                            label: "Delete",
                            color: "red-500",
                            type: "delete",
                            hasSeparator: true,
                            data: row.original,
                        },
                        "Delete"
                    ),
                    h(
                        ActionLink,
                        {
                            to: "/settings",
                            hasSeparator: true,
                            label: "View",
                            type: "view",
                            data: row.original,
                        },
                        "View"
                    ),
                ]),
            ]);
        },
    },
    {
        accessorKey: "status",
        header: () => h("div", { class: "text-left" }, "Status"),
        cell: ({ row }) => {
            return h(
                "div",
                { class: "text-left font-medium" },
                row.getValue("status")
            );
        },
    },
    {
        accessorKey: "author",
        header: () => h("div", { class: "text-left" }, "Author"),
        cell: ({ row }) => {
            return h(
                "div",
                { class: "text-left font-medium" },
                row.getValue("author")
            );
        },
    },
    {
        accessorKey: "id",
        header: () => h("div", { class: "text-left" }, "Shortcode"),
        cell: ({ row }) => {
            const id = Number.parseFloat(row.getValue("id"));
            const shortcode = "[wprb-subreddit-feed feed=" + id + "]";
            const copyToClipboard = () => {
                if (navigator.clipboard) {
                    navigator.clipboard
                        .writeText(shortcode)
                        .then(() => {
                            alert("Shortcode copied to clipboard!");
                        })
                        .catch((err) => {
                            console.error("Failed to copy: ", err);
                        });
                } else {
                    console.warn("Clipboard API not available");
                }
            };
            return h(
                "code",
                {
                    onClick: copyToClipboard,
                    style: { cursor: "pointer" },
                    title: "Click to copy",
                },
                shortcode
            );
        },
    },
    {
        id: "actions",
        enableHiding: false,
        header: () => h("div", { class: "text-left" }, "Actions"),
        cell: ({ row }) => {
            const payment = row.original;

            return h(
                "div",
                { class: "relative" },
                h(DropdownAction, {
                    payment,
                })
            );
        },
    },
];
