import AbstractApiRepository from '@wexample/js-api-entity/Common/AbstractApiRepository';
import DemoRoom from '../Entity/DemoRoom.js';

export default class DemoRoomRepository extends AbstractApiRepository<DemoRoom> {
  static getEntityType() {
    return DemoRoom;
  }
}
