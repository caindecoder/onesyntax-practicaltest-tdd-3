import { PostGateway } from './PostGateway';

export async function postFetch() {
    const gateway = new PostGateway();
    return await gateway.fetchAll();
}
