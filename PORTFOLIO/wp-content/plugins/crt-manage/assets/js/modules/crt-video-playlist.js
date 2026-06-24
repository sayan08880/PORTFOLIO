(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-video-playlist.default',function($scope) {

            const crtVideoPlaylist = {
                players: {},
                isAPIReady: false,
                pendingPlayers: [],
                apiLoaded: false,

                init: function($widget) {
                    if (!$widget || !$widget.length) return;
                    // Load YouTube IFrame API only once
                    if (!this.apiLoaded && typeof YT === 'undefined') {
                        const tag = document.createElement('script');
                        tag.src = "//www.youtube.com/iframe_api";
                        const firstScriptTag = document.getElementsByTagName('script')[0];
                        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
                        this.apiLoaded = true;

                        window.onYouTubeIframeAPIReady = () => {
                            crtVideoPlaylist.isAPIReady = true;
                            crtVideoPlaylist.pendingPlayers.forEach(playerData => {
                                crtVideoPlaylist.createPlayer(playerData.$container, playerData.videoId, playerData.$widget);
                            });
                        };
                    }

                    const urls = $widget.find('.crt-vplaylist-thumbs').data('urls');
                    if (urls && Array.isArray(urls)) {
                        this.processVideos($widget, urls);
                    }
                },

                processVideos: function($widget, urls) {
                    const $playlistUl = $widget.find('.crt-vplaylist-thumbs ul');
                    const title_tag = $widget.find('.crt-vplaylist-current-title').prop('tagName').toLowerCase();

                    const videoPromises = urls.map((url, index) => {
                        return new Promise(resolve => {
                            const videoId = crtVideoPlaylist.extractVideoId(url);
                            if (!videoId) {
                                resolve(null);
                                return;
                            }

                            $.get('https://www.youtube.com/oembed', {
                                url: url,
                                format: 'json'
                            })
                                .done(response => {
                                    resolve({
                                        videoId: videoId,
                                        title: response.title,
                                        index: index
                                    });
                                })
                                .fail(() => {
                                    resolve(null);
                                });
                        });
                    });

                    Promise.all(videoPromises).then(results => {
                        const validResults = results.filter(r => r !== null).sort((a, b) => a.index - b.index);

                        validResults.forEach((video, index) => {
                            const $li = $('<li>').attr('data-video', video.videoId);
                            const thumbnailUrl = 'https://i.ytimg.com/vi/' + video.videoId + '/maxresdefault.jpg';

                            $li.append($('<img>').attr({
                                'src': thumbnailUrl,
                                'alt': video.title
                            }));

                            const $info = $('<div>').addClass('crt-vplaylist-info');
                            $info.append($('<' + title_tag + '>').addClass('crt-vplaylist-info-title').text(video.title));
                            $li.append($info);
                            $playlistUl.append($li);

                            if (index === 0) {
                                $widget.find('.crt-vplaylist-current-title').text(video.title);
                                $widget.find('.crt-vplaylist-highlight').attr('data-video', video.videoId);

                                const $player = $widget.find('.crt-vplaylist-main');
                                if (crtVideoPlaylist.isAPIReady) {
                                    crtVideoPlaylist.createPlayer($player, video.videoId, $widget);
                                } else {
                                    crtVideoPlaylist.pendingPlayers.push({
                                        $container: $player,
                                        videoId: video.videoId,
                                        $widget: $widget
                                    });
                                }
                            }
                        });

                        $widget.find('.crt-vplaylist-thumbs li').on('click', function() {
                            const videoId = $(this).data('video');
                            const playerId = $widget.find('.crt-vplaylist-main').attr('id');
                            const videoTitle = $(this).find('.crt-vplaylist-info-title').text();

                            if (crtVideoPlaylist.players[playerId]) {
                                crtVideoPlaylist.players[playerId].loadVideoById(videoId);
                                $widget.find('.crt-vplaylist-highlight').attr('data-video', videoId);
                                $widget.find('.crt-vplaylist-current-title').text(videoTitle);
                                $widget.find('.crt-play').hide();
                                $widget.find('.crt-pause').show();
                            }
                        });
                    });
                },

                createPlayer: function($container, videoId, $widget) {
                    const playerId = 'crt-player-' + Math.random().toString(36).substr(2, 9);
                    $container.attr('id', playerId);

                    this.players[playerId] = new YT.Player(playerId, {
                        height: '360',
                        width: '640',
                        videoId: videoId,
                        playerVars: {
                            'autoplay': 0,
                            'controls': 1,
                            'rel': 0,
                            'showinfo': 0
                        },
                        events: {
                            'onReady': function() {
                                $widget.find('.crt-vplaylist-controller').on('click', function() {
                                    const player = crtVideoPlaylist.players[playerId];

                                    if (player.getPlayerState() === YT.PlayerState.PLAYING) {
                                        player.pauseVideo();
                                        $(this).find('.crt-play').show();
                                        $(this).find('.crt-pause').hide();
                                    } else {
                                        player.playVideo();
                                        $(this).find('.crt-play').hide();
                                        $(this).find('.crt-pause').show();
                                    }
                                });
                            },
                            'onStateChange': function(event) {
                                const $controller = $widget.find('.crt-vplaylist-controller');

                                if (event.data === YT.PlayerState.PLAYING) {
                                    $controller.find('.crt-play').hide();
                                    $controller.find('.crt-pause').show();
                                } else if (event.data === YT.PlayerState.PAUSED || event.data === YT.PlayerState.ENDED) {
                                    $controller.find('.crt-play').show();
                                    $controller.find('.crt-pause').hide();
                                }
                            }
                        }
                    });

                    return playerId;
                },

                extractVideoId: function(url) {
                    const pattern = /(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i;
                    const match = url.match(pattern);
                    return match ? match[1] : null;
                }
            };

            crtVideoPlaylist.init($scope);
        });
    });
})(jQuery);