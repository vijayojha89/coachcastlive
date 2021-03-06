/* global AccCore */
//f370782db6a592c836700ac30ed325f1ced5ec42
let otCore;
var el = document.getElementById('credentials');
let credentials = JSON.parse(el.getAttribute('data'));
let userName = document.getElementById('userName').value;
el.remove();
const options = {


    credentials: credentials,

    // A container can either be a query selector or an HTMLElement
    // eslint-disable-next-line no-unused-vars
    streamContainers: function streamContainers(pubSub, type, data) {
        return {
            publisher: {
                camera: '#cameraPublisherContainer',
                screen: '#screenPublisherContainer',
            },
            subscriber: {
                camera: '#cameraSubscriberContainer',
                screen: '#screenSubscriberContainer',
            },
        }[pubSub][type];
    },
    controlsContainer: '#controls',
    packages: ['textChat', 'annotation'],
    communication: {
        callProperties: null, // Using default
    },
    textChat: {
        name: userName, // eslint-disable-line no-bitwise
        waitingMessage: 'Welcome here you can chat..',
        container: '#chat',
        alwaysOpen: true,
    },
    screenSharing: {
        extensionID: 'plocfffmbcclpdifaikiikgplfnepkpo',
        annotation: true,
        externalWindow: false,
        dev: true,
        screenProperties: null, // Using default
    },
    annotation: {

    },
    archiving: {
        startURL: 'https://example.com/startArchive',
        stopURL: 'https://example.com/stopArchive',
    },
};

/** Application Logic */
const app = function() {
    const state = {
        connected: false,
        active: false,
        publishers: null,
        subscribers: null,
        meta: null,
        localAudioEnabled: true,
        localVideoEnabled: true,
    };

    /**
     * Update the size and position of video containers based on the number of
     * publishers and subscribers specified in the meta property returned by otCore.
     */

    const updateVideoContainers = () => {
        const { meta } = state;

        const sharingScreen = meta ? !!meta.publisher.screen : false;
        const viewingSharedScreen = meta ? meta.subscriber.screen : false;
        const activeCameraSubscribers = meta ? meta.subscriber.camera : 0;


        const videoContainerClass = `App-video-container ${(sharingScreen || viewingSharedScreen) ? 'center' : ''}`;
        document.getElementById('appVideoContainer').setAttribute('class', videoContainerClass);

        const cameraPublisherClass =
            `video-container ${!!activeCameraSubscribers || sharingScreen ? 'small' : ''} ${!!activeCameraSubscribers || sharingScreen ? 'small' : ''} ${sharingScreen || viewingSharedScreen ? 'left' : ''}`;
        document.getElementById('cameraPublisherContainer').setAttribute('class', cameraPublisherClass);

        const screenPublisherClass = `video-container ${!sharingScreen ? 'hidden' : ''}`;
        document.getElementById('screenPublisherContainer').setAttribute('class', screenPublisherClass);

        const cameraSubscriberClass =
            `video-container ${!activeCameraSubscribers ? 'hidden' : ''} active-${activeCameraSubscribers} ${viewingSharedScreen || sharingScreen ? 'small' : ''}`;
        document.getElementById('cameraSubscriberContainer').setAttribute('class', cameraSubscriberClass);

        const screenSubscriberClass = `video-container ${!viewingSharedScreen ? 'hidden' : ''}`;
        document.getElementById('screenSubscriberContainer').setAttribute('class', screenSubscriberClass);

        if (meta.publisher.total == 1 && meta.subscriber.total) {
            startTimer();
            document.getElementById('chat').setAttribute('class', 'App-chat-container');
            document.getElementById('timerSection').setAttribute('class', 'timerSection');
        } else {
            if (window.x != undefined && window.x != 'undefined') {
                window.clearInterval(window.x);
            }

            document.getElementById('timerSection').setAttribute('class', 'timerSection hidden');
            document.getElementById('chat').setAttribute('class', 'App-chat-container hidden');
        }

    };

    /**
     * Update the UI
     * @param {String} update - 'connected', 'active', or 'meta'
     */

    const updateUI = (update) => {
        const { connected, active } = state;

        switch (update) {
            case 'connected':
                if (connected) {
                    document.getElementById('connecting-mask').classList.add('hidden');
                    //document.getElementById('start-mask').classList.remove('hidden');
                    startCall();
                    //startTimer();
                }
                break;
            case 'active':
                if (active) {
                    document.getElementById('cameraPublisherContainer').classList.remove('hidden');
                    document.getElementById('start-mask').classList.add('hidden');
                    document.getElementById('controls').classList.remove('hidden');
                }
                break;
            case 'meta':
                updateVideoContainers();
                break;
            default:
                console.log('nothing to do, nowhere to go');
        }
    };

    /**
     * Update the state and UI
     */
    const updateState = function(updates) {
        Object.assign(state, updates);
        Object.keys(updates).forEach(update => updateUI(update));
    };

    /**
     * Start publishing video/audio and subscribe to streams
     */
    const startCall = function() {
        otCore.startCall()
            .then(function({ publishers, subscribers, meta }) {
                updateState({ publishers, subscribers, meta, active: true });
            }).catch(function(error) { console.log(error); });
    };

    /**
     * Toggle publishing local audio
     */
    const toggleLocalAudio = function() {
        const enabled = state.localAudioEnabled;
        otCore.toggleLocalAudio(!enabled);
        updateState({ localAudioEnabled: !enabled });
        const action = enabled ? 'add' : 'remove';
        document.getElementById('toggleLocalAudio').classList[action]('muted');
    };

    /**
     * Toggle publishing local video
     */
    const toggleLocalVideo = function() {
        const enabled = state.localVideoEnabled;
        otCore.toggleLocalVideo(!enabled);
        updateState({ localVideoEnabled: !enabled });
        const action = enabled ? 'add' : 'remove';
        document.getElementById('toggleLocalVideo').classList[action]('muted');
    };

    /**
     * Subscribe to otCore and UI events
     */
    const createEventListeners = function() {
        const events = [
            'subscribeToCamera',
            'unsubscribeFromCamera',
            'subscribeToScreen',
            'unsubscribeFromScreen',
            'startScreenShare',
            'endScreenShare',
        ];
        events.forEach(event => otCore.on(event, ({ publishers, subscribers, meta }) => {
            updateState({ publishers, subscribers, meta });
        }));

        //document.getElementById('start').addEventListener('click', startCall);
        document.getElementById('toggleLocalAudio').addEventListener('click', toggleLocalAudio);
        document.getElementById('toggleLocalVideo').addEventListener('click', toggleLocalVideo);
    };

    /**
     * Initialize otCore, connect to the session, and listen to events
     */
    const init = function() {
        otCore = new AccCore(options);
        otCore.connect().then(function() { updateState({ connected: true }); });
        createEventListeners();
    };


    const startTimer = function() {
        document.getElementById('timerSection').classList.remove('hidden');
        var countDownDate = new Date();
        countDownDate = new Date(countDownDate.setMinutes(countDownDate.getMinutes() + 15));

        // Update the count down every 1 second
        window.x = setInterval(function() {

            // Get todays date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            minutes = (minutes < 10) ? "0" + minutes : minutes;
            seconds = (seconds < 10) ? "0" + seconds : seconds;
            // Display the result in the element with id="demo"
            document.getElementById("m_timer").innerHTML = minutes + ":" + seconds;

            // If the count down is finished, write some text 
            if (distance < 0) {
                //clearInterval(x);
                window.clearInterval(window.x);
                document.getElementById("m_timer").innerHTML = "EXPIRED";
            }
        }, 1000);
    };

    init();
};

document.addEventListener('DOMContentLoaded', app);