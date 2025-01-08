import { h } from "vue";
import ActionLink from "./ActionLink.vue";

export const columns = [
    {
        accessorKey: "post_title",
        header: () => h("div", { class: "text-left" }, "Title"),
        cell: ({ row }) => {
            return h("div", { class: "text-left font-medium" }, [
                h("span", row.getValue("post_title")),
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
        accessorKey: "post_status",
        header: () => h("div", { class: "text-left" }, "Status"),
        cell: ({ row }) => {
            return h(
                "div",
                { class: "text-left font-medium" },
                row.getValue("post_status")
            );
        },
    },
    {
        accessorKey: "ID",
        header: () => h("div", { class: "text-left" }, "Shortcode"),
        cell: ({ row }) => {
            const id = Number.parseFloat(row.getValue("ID"));
            const shortcode = "[wprb-subreddit-feed id=" + id + "]";
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
];
