jQuery(document).ready(function(a){function r(){const e=g({route:"get-feed-posts",feed_id:46});console.log(e),fetch(e).then(t=>{if(!t.ok)throw new Error("Network response was not ok "+t.statusText);return t.json()}).then(t=>o(t.data)).catch(t=>{console.error("There was a problem with the fetch operation:",t),a("#error-message").text("Failed to load data. Please try again later.")})}r();function o(i){var e=i.config,t=i.data,s=a("#wprb-subreddit-feed");if(e.title.show){var d=e.title.tag,u=e.title.classes,p=`<${d} class="${u}">${t.subRedditInfo.title}</${d}>`;s.append(p)}if(e.description.show){var c=e.description.tag,w=e.description.classes,h=`<${c} class="${w}">${t.subRedditInfo.description}</${c}>`;s.append(h)}if(e.list.show){var l=e.list.tag,$=e.list.classes,f=a(`<${l} class="${$}"></${l}>`);t.posts.forEach(function(n){var v=`
                        <li>
                            <h2><a href="${n.url}">${n.title}</a></h2>
                            <p>${new Date(n.created_utc*1e3).toLocaleString()}</p>
                            <p>${n.score} points | ${n.num_comments} comments</p>
                            ${n.selftext?`<div>${n.selftext}</div>`:""}
                        </li>
                    `;f.append(v)}),s.append(f)}}});function g(a,r=""){a.action="wp_base_plugin_frontend",a.nonce=window.wpBasePluginFrontend.nonce;const o=new URLSearchParams(a).toString();return r||(r=window.wpBasePluginFrontend.ajaxurl+"?"+o),r}
