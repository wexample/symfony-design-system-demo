import demoMessage from '../data/entity/demo_message.json';
import demoRoom from '../data/entity/demo_room.json';

type EntitySchema = { name: string };

export default function getGeneratedEntitySchemas(): Record<string, EntitySchema> {
  return {
    [demoMessage.name]: demoMessage,
    [demoRoom.name]: demoRoom,
  };
}
