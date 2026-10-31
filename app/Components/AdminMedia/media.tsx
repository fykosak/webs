import React, { useState } from 'react';
import { createPortal, render } from 'react-dom';
import { netteFetch } from '../../../vendor/fykosak/nette-fks-utils/src/FrontendComponent/fetch/fech';
import { NetteActions } from '../../../vendor/fykosak/nette-fks-utils/src/FrontendComponent/NetteActions/nette-actions';

interface Photo {
    name: string;
    extension: string;
    path: string;
    thumbSrc: string;
}

interface Media {
    photos: Photo[];
}

interface FlashMessage {
    text: string;
    level: string;
}

interface AdminMediaGalleryProps {
    initialData: Media;
    initialActions: NetteActions;
}

interface PhotoFormProps extends Photo {
    onDelete: (photo: Photo) => void;
    onRename: (photo: Photo) => void;
}


function AdminMediaGallery({ initialData, initialActions }: AdminMediaGalleryProps) {
    const [data, setData] = useState(initialData);
    const [actions, setActions] = useState(initialActions);
    const [flashMessages, setFlashMessages] = useState<FlashMessage[]>([]);

    async function sendRequest(action: string, body: BodyInit): Promise<void> {
        const response = await netteFetch<Media>(action, body);
        setData(response.data);
        setActions(response.actions);

        const messages = response.messages.map(message => ({
            text: message.text,
            level: message.level,
        }));
        setFlashMessages(messages);
    }

    async function deletePhoto(photo: Photo): Promise<void> {
        const body = new FormData();
        body.set('name', photo.name);
        body.set('extension', photo.extension);
        body.set('path', photo.path);

        setFlashMessages([]);
        try {
            await sendRequest(actions.getAction('delete'), body);
        } catch (deleteError) {
            setFlashMessages([
                {
                    text: deleteError instanceof Error ? deleteError.message : `Nepodařilo se smazat soubor ${photo.name}.`,
                    level: 'danger',
                }
            ]);
        }
    }

    async function renamePhoto(photo: Photo): Promise<void> {
        console.log('rename');
    }

    const flashMessagesHTML = flashMessages.map(flashMessage =>
        <div className={`alert alert-dismissible fade show shadow-sm alert-${flashMessage.level}`} role="alert">
            <i className={`me-2 fa ${flashMessage.level === 'danger' ? 'fa-triangle-exclamation' : 'fa-circle-check'}`}></i>
            {flashMessage.text}
            <button type="button" className="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    )

    const flashMessagesElement = document.getElementById('media-flash-messages');

    const photos = data.photos.map(photo => <PhotoForm key={photo.name} {...photo} onDelete={deletePhoto} onRename={renamePhoto}/>);

    return <>
                {flashMessagesElement && createPortal(flashMessagesHTML, flashMessagesElement)}
                <div className="d-flex flex-wrap gap-3">
                    {photos}
                </div>
            </>
}

function PhotoForm({ onDelete, onRename, ...photo }: PhotoFormProps) {
    return <div className="card photo-card" style={{ width: '18rem' }}>
                <img className="card-img-top" src={photo.thumbSrc} />
                <div className="photo-overlay d-flex flex-column justify-content-center align-items-center">
                    <h5 className="text-white text-center px-2">{photo.name}.{photo.extension}</h5>
                    <div className="d-flex">
                        <button
                            type="button"
                            className="btn btn-outline-primary me-2"
                            onClick={() => void onRename(photo)}
                        >
                            <i className="fa-solid fa-square-pen"></i>
                            <span> Přejmenovat </span>
                        </button>
                        <button
                            type="button"
                            className="btn btn-outline-danger"
                            onClick={() => void onDelete(photo)}
                        >
                            <i className="fa-solid fa-times"></i>
                            <span> Smazat </span>
                        </button>
                    </div>
                </div>
            </div>
}

const mediaElement = document.querySelector<HTMLElement>('[data-frontend-id="admin.media"]');
if (mediaElement) {
    const initialData = JSON.parse(mediaElement.dataset.data ?? '{}') as Media;
    const initialActions = new NetteActions(JSON.parse(mediaElement.dataset.actions ?? '{}'));
    render(<AdminMediaGallery initialData={initialData} initialActions={initialActions} />, mediaElement);
}
