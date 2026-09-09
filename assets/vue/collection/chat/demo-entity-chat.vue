<script>
import AbstractEntityChat from '@wexample/symfony-design-system/vue/collection/chat/abstract-entity-chat.vue';

const DEMO_MESSAGES = [
  { id: 1, author: 'Maya', content: 'Can you summarize yesterday\'s sync?', createdAt: '2026-03-02T09:21:00' },
  { id: 2, author: 'Assistant', content: 'Sure, here are the decisions we can execute this week.', createdAt: '2026-03-02T09:22:00' },
  { id: 3, author: 'Maya', content: 'Keep it short, it goes into the sidebar.', createdAt: '2026-03-02T09:23:00' },
];

export default {
  extends: AbstractEntityChat,

  template: '#vue-template-wexample-symfony-design-system-demo-bundle-vue-collection-chat-demo-entity-chat',

  methods: {
    getEntityClass() {
      return null;
    },

    // Stands in for the API: the component still goes through createEntity(),
    // so the composer is exercised the way a real chat would exercise it.
    getEntityRepository() {
      return {
        createEntity: async (entity) => {
          DEMO_MESSAGES.push(entity);
          return entity;
        },
      };
    },

    async refreshEntitiesCollection() {
      this.entities = [...DEMO_MESSAGES];
    },

    buildMessageEntity(content) {
      return {
        id: DEMO_MESSAGES.length + 1,
        author: 'Maya',
        content,
        createdAt: new Date().toISOString(),
      };
    },
  },
};
</script>
