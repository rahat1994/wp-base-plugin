import "./base.css";

jQuery(document).ready(function ($) {
    var feedWrapper = $(document).find("#wprb-subreddit-feed-wrapper");
    var feedId = feedWrapper.data("feed");

    let currentPage = 1;

    // Function to fetch data and process it
    function fetchData(page = 1) {
        const args = {
            route: "get-feed-posts",
            feed_id: feedId,
            page: page,
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
            .then((data) => renderSubredditFeed(data.data))
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

    // Function to render the subreddit feed
    function renderSubredditFeed(data) {
        var config = data.config;
        var subredditData = data.data;
        var $feed = $("#wprb-subreddit-feed");

        if (
            config.title.show &&
            currentPage === 1 &&
            subredditData.subRedditInfo.title !== null
        ) {
            var titleTag = config.title.tag;
            var titleClasses = config.title.classes;
            var titleHtml = `<${titleTag} class="${titleClasses}">${subredditData.subRedditInfo.title}</${titleTag}>`;
            $feed.append(titleHtml);
        }

        // Render description if show is true
        if (
            config.description.show &&
            currentPage === 1 &&
            subredditData.subRedditInfo.description !== null
        ) {
            var descriptionTag = config.description.tag;
            var descriptionClasses = config.description.classes;
            var descriptionHtml = `<${descriptionTag} class="${descriptionClasses}">${subredditData.subRedditInfo.description}</${descriptionTag}>`;
            $feed.append(descriptionHtml);
        }

        // Render posts if list show is true
        if (config.list.show) {
            var listTag = config.list.tag;
            var listClasses = config.list.classes;
            var $list = $feed.find(
                `${listTag}${listClasses ? "." + listClasses : ""}`
            );

            if ($list.length === 0) {
                $list = $(`<span>No posts found</span>`);
                $feed.append($list);
            }

            subredditData.posts.forEach(function (post) {
                var postHtml = `<div class="wprb-post">
                        <li>
                            <${config.title.tag} class="${
                    config.title.classes
                }">
                                <a href="${post.url}">${post.title}</a>
                            </${config.title.tag}>
                            <p>${new Date(
                                post.created_utc * 1000
                            ).toLocaleString()}</p>
                            <p>${post.score} points | ${
                    post.num_comments
                } comments</p>
                            ${
                                post.selftext
                                    ? `<div>${post.selftext.substring(
                                          0,
                                          60
                                      )}...</div>`
                                    : ""
                            }
                       </li></div>
                    `;
                $list.append(postHtml);
            });

            // Add "Load More" link if it doesn't exist
            if ($feed.find("#load-more").length === 0) {
                $feed.append('<a href="#" id="load-more">Load More</a>');
            }

            // remove the "Load More" link if the post length is less than the 10
            if (subredditData.posts.length < 10) {
                $feed.find("#load-more").remove();
            }
        }
    }

    // Event handler for "Load More" link
    $(document).on("click", "#load-more", function (e) {
        e.preventDefault();
        currentPage++;
        fetchData(currentPage);
    });
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
