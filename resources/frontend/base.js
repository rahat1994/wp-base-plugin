jQuery(document).ready(function ($) {
    // Function to fetch data and process it
    function fetchData() {
        const args = {
            route: "get-feed-posts",
            feed_id: 46,
        };

        const url = buildAjaxGetUrl(args);
        console.log(url);
        fetch(url) // Replace with your API endpoint
            .then((response) => {
                if (!response.ok) {
                    throw new Error(
                        "Network response was not ok " + response.statusText
                    );
                }
                return response.json();
            })
            .then((data) => {
                // Display title and description
                $("#title").text(data.title);
                $("#description").text(data.description);

                // Loop through posts and append them to the page
                const postsContainer = $("#posts-container");
                postsContainer.empty(); // Clear any existing content

                data.posts.forEach((post) => {
                    const postElement = $(
                        `<div class="post">
                            <h3>${post.text}</h3>
                            <p>${post.description}</p>
                        </div>`
                    );
                    postsContainer.append(postElement);
                });
            })
            .catch((error) => {
                console.error(
                    "There was a problem with the fetch operation:",
                    error
                );
                $("#error-message").text(
                    "Failed to load data. Please try again later."
                );
            });
    }

    // Call fetchData on page load
    fetchData();
});

function buildAjaxGetUrl(args, url = "") {
    args.action = "wp_base_plugin_frontend";
    args.nonce = window.wpBasePluginFrontend.nonce;
    const queryString = new URLSearchParams(args).toString();
    if (!url) {
        url = window.wpBasePluginFrontend.ajaxurl + "?" + queryString;
    }

    return url;
}
