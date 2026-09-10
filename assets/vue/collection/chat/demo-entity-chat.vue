<script>
import AbstractEntityChat from '@wexample/symfony-design-system/vue/collection/chat/abstract-entity-chat.vue';
import LiveUpdatesService from '@wexample/symfony-loader/js/Services/LiveUpdatesService';
import DemoMessage from '../../../Entity/DemoMessage';

const ICON_BY_TYPE = {
  assistant: 'ph:bold/robot',
  error: 'ph:bold/warning-circle',
  system: 'ph:bold/info',
  tool: 'ph:bold/wrench',
  user: 'ph:bold/user'
};

// What the server publishes on the room's topic when a message is written.
const EVENT_MESSAGE_CREATED = 'demo-message-created';

export default {
  extends: AbstractEntityChat,

  template: '#vue-template-wexample-symfony-design-system-demo-bundle-vue-collection-chat-demo-entity-chat',

  props: {
    // The room the thread is held in. The page makes it and names it, and it is
    // also the only thing the browser can subscribe to before a message exists.
    roomId: {
      type: String,
      required: true
    }
  },

  data() {
    return {
      liveConnection: null
    };
  },

  mounted() {
    this.runWhenAppReady(() => this.subscribeToRoom());
  },

  beforeUnmount() {
    this.liveConnection?.close();
    this.liveConnection = null;
  },

  methods: {
    getEntityClass() {
      return DemoMessage;
    },

    getEntitiesFetchParams() {
      return {
        query: {
          room: this.roomId
        }
      };
    },

    getPageLength() {
      return 20;
    },

    startsAtLastPage() {
      return true;
    },

    // A message has no author of its own: who spoke is its type.
    getMessageAuthor(entity) {
      return this.trans(`@vue::author.${entity.type}`);
    },

    getMessageContent(entity) {
      return entity.body ?? '';
    },

    getMessageDate(entity) {
      return entity.dateCreated ?? null;
    },

    getMessageIcon(entity) {
      return ICON_BY_TYPE[entity.type] ?? ICON_BY_TYPE.user;
    },

    getMessageVariant(entity) {
      return entity.type;
    },

    buildMessageEntity(content) {
      return new DemoMessage({
        room: this.roomId,
        body: content
      });
    },

    async subscribeToRoom() {
      this.liveConnection = await this.app
        .getServiceOrFail(LiveUpdatesService)
        .connectToEntity({
          entityName: 'demo-room',
          id: this.roomId,
          onMessage: (connection, payload) => this.onLiveMessage(payload)
        });
    },

    // The thread is refetched rather than appended to: the message just
    // published is also the one the sender already has, and asking again is
    // shorter than telling the two apart.
    onLiveMessage(payload) {
      if (payload?.event === EVENT_MESSAGE_CREATED) {
        this.refreshEntitiesCollection();
      }
    }
  }
};
</script>
