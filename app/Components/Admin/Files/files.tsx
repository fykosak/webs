import React, { ChangeEvent, useState } from 'react';
import { createPortal, render } from 'react-dom';
import { netteFetch } from '../../../../vendor/fykosak/nette-fks-utils/src/FrontendComponent/fetch/fech';
import { NetteActions } from '../../../../vendor/fykosak/nette-fks-utils/src/FrontendComponent/NetteActions/nette-actions';

interface FileData {
    name: string;
    extension: string;
    path: string;
}

interface Files {
    files: FileData[];
}

interface FlashMessage {
    text: string;
    level: string;
}

interface FileManagementListProps {
    initialData: Files;
    initialActions: NetteActions;
}

interface FileFormProps extends FileData {
    onDelete: (file: FileData) => void;
    onRename: (file: FileData, newName: string) => void;
    onUpload: (event: ChangeEvent<HTMLInputElement>) => void;
}


function FileManagementList({ initialData, initialActions }: FileManagementListProps) {
    const [data, setData] = useState(initialData);
    const [actions, setActions] = useState(initialActions);
    const [flashMessages, setFlashMessages] = useState<FlashMessage[]>([]);

    async function sendRequest(action: string, body: BodyInit): Promise<void> {
        const response = await netteFetch<Files>(action, body);
        setData(response.data);
        setActions(response.actions);

        const messages = response.messages.map(message => ({
            text: message.text,
            level: message.level,
        }));
        setFlashMessages(messages);
    }

    async function uploadFiles(event: ChangeEvent<HTMLInputElement>): Promise<void> {
        const uploadedFiles = Array.from(event.currentTarget.files ?? []);

        setFlashMessages([]);

        let errorMessages = [];
        for (const file of uploadedFiles) {
            const body = new FormData();
            body.append('file', file);
            try {
                await sendRequest(actions.getAction('upload'), body);
            } catch (uploadError) {
                errorMessages.push(
                    {
                        text: uploadError instanceof Error ? uploadError.message : 'Nahrání souboru se nezdařilo.',
                        level: 'danger',
                    }
                )
            }
        }

        if (errorMessages.length > 0) {
            setFlashMessages(errorMessages);
        }
    }

    async function deleteFile(file: FileData): Promise<void> {
        const body = new FormData();
        body.set('name', file.name);
        body.set('extension', file.extension);
        body.set('path', file.path);

        setFlashMessages([]);
        try {
            await sendRequest(actions.getAction('delete'), body);
        } catch (deleteError) {
            setFlashMessages([
                {
                    text: deleteError instanceof Error ? deleteError.message : `Nepodařilo se smazat soubor ${file.name}.`,
                    level: 'danger',
                }
            ]);
        }
    }

    async function renameFile(file: FileData, newName: string): Promise<void> {
        const body = new FormData();
        body.set('old-name', file.name);
        body.set('new-name', newName);
        body.set('extension', file.extension);
        body.set('path', file.path);

        setFlashMessages([]);
        try {
            await sendRequest(actions.getAction('rename'), body);
        } catch (renameError) {
            setFlashMessages([
                {
                    text: renameError instanceof Error ? renameError.message : `Nepodařilo se přejmenovat soubor ${file.name}.`,
                    level: 'danger',
                }
            ]);
        }
    }

    const flashMessagesHTML = flashMessages.map(flashMessage =>
        <div className={`alert alert-dismissible fade show shadow-sm alert-${flashMessage.level}`} role="alert">
            <i className={`me-2 fa ${flashMessage.level === 'danger' ? 'fa-triangle-exclamation' : 'fa-circle-check'}`}></i>
            {flashMessage.text}
            <button type="button" className="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    )

    const flashMessagesElement = document.getElementById('file-flash-messages');

    const files = data.files.map(file => <FileForm key={file.name} {...file} onDelete={deleteFile} onRename={renameFile} onUpload={uploadFiles}/>);

    return <>
                {flashMessagesElement && createPortal(flashMessagesHTML, flashMessagesElement)}

                <h5>Nahrát nové soubory:</h5>
                <div className="file-upload-container mb-3">
                    <label className="d-flex justify-content-start align-items-center border rounded my-2 border-dark-subtle file-upload">
                        <span className="d-flex">
                            <span className="btn btn-outline-primary">
                                <i className="fa-solid fa-search" aria-hidden="true"></i>
                            </span>
                        </span>
                        <span className="mx-3">
                            Vyberte soubory k nahrání...
                        </span>
                        <input
                            className="visually-hidden"
                            type="file"
                            accept=".pdf"
                            multiple
                            disabled={false}
                            onChange={uploadFiles}
                        />
                    </label>
                </div>

                <h5>Nahrané soubory:</h5>
                {files.length === 0 ?
                    <p>Nejsou nahrané žádné soubory.</p>
                    :
                    <div className="file-editor-container">
                        {files}
                    </div>
                }
            </>
}

function FileForm({ onDelete, onRename, onUpload, ...file }: FileFormProps) {
    const [isActive, setIsActive] = useState(false);
    const [newName, setNewName] = useState('');

    return <div className="d-flex justify-content-between align-items-center border rounded my-2 border-dark-subtle file-editor">
                { isActive ? (
                    <form
                    className="d-flex flex-column align-items-center mb-2"
                    data-bs-theme="dark"
                    onSubmit={
                        event => {
                            event.preventDefault();
                            void onRename(file, newName);
                        }
                    }>
                        <div className='d-flex align-items-center mb-2'>
                            <input
                                name="fileName"
                                value={newName}
                                className="align-items-center form-control text-white px-2"
                                onChange={event => setNewName(event.currentTarget.value)}
                            />
                            <span className="text-white ms-2">{'.'+file.extension}</span>
                        </div>
                        <div className="d-flex py-2">
                            <button
                                type="submit"
                                className="btn btn-outline-primary me-3"
                            >
                                <i className="fa-solid fa-floppy-disk"></i>
                                <span>Uložit</span>
                            </button>
                            <button
                                type="button"
                                className="btn btn-outline-danger me-2"
                                onClick={() => void setIsActive(!isActive)}
                            >
                                <i className="fa-solid fa-times"></i>
                                <span>Zrušit</span>
                            </button>
                        </div>
                    </form>
                    ) : (
                    <div className="d-flex align-items-center">
                        <div className="ms-2">
                            {`${file.name}.${file.extension}`}
                        </div>
                        <span className="d-flex ms-5">
                            <a href={`${file.path}/${file.name}.${file.extension}`} target="_blank" rel="noopener noreferrer">
                                <button type="button" className="btn btn-outline-warning">
                                    <i className="fa-solid fa-eye"></i>
                                </button>
                            </a>
                            <button
                                type="button"
                                className="btn btn-outline-primary mx-2"
                                onClick={
                                    () => {
                                        setIsActive(!isActive);
                                        setNewName(file.name);
                                    }
                                }
                            >
                                <i className="fa-solid fa-pen"></i>
                            </button>
                            <button
                                type="button"
                                className="btn btn-outline-danger"
                                onClick={() => void onDelete(file)}
                            >
                                <i className="fa-solid fa-trash-can"></i>
                            </button>
                        </span>
                    </div>
                    )
                }
            </div>
}

const filesElement = document.querySelector<HTMLElement>('[data-frontend-id="admin.files"]');
if (filesElement) {
    const initialData = JSON.parse(filesElement.dataset.data ?? '{}') as Files;
    const initialActions = new NetteActions(JSON.parse(filesElement.dataset.actions ?? '{}'));
    render(<FileManagementList initialData={initialData} initialActions={initialActions} />, filesElement);
}