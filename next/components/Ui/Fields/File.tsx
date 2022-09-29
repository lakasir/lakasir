import { DocumentIcon, UploadIcon } from "@heroicons/react/outline";
import Image from "next/image";
import { ChangeEvent, useEffect, useRef, useState } from "react";
import { Modal } from "../Modals";

interface IFilePickerInterface {
  name: string;
  label: string | JSX.Element;
  accept?: string;
  multiple?: boolean;
  onChange?: (e: ChangeEvent<HTMLInputElement>, file: FileList | null) => void;
}

interface FilesPickerProps {
  fileName: string;
  fileType: string;
  fileSize: number;
  filePreview: string;
}

const FilePicker = (props: IFilePickerInterface) => {
  const fileInput = useRef(null);
  const [files, setFiles] = useState<FilesPickerProps>();
  const [filesList, setFilesList] = useState<FilesPickerProps[]>([]);
  const [showModal, setShowModal] = useState(false);
  useEffect(() => {
    if (files != undefined) {
      setFilesList([files, ...filesList]);
    }
    // console.log(files);
  }, [files]);
  return (
    <>
      <Modal open={showModal} onClose={(status) => setShowModal(status)}>
        {/* create preview image from src */}
        <></>
      </Modal>
      <input
        type="file"
        name={props.name}
        accept={props.accept}
        ref={fileInput}
        onChange={(e) => {
          if (fileInput.current) {
            const current = fileInput.current as HTMLInputElement;
            if (current.files != null) {
              // TODO: <29-09-22, sheenazien8>
              // convert size to mb
              // create file preview if the file is not images
              const fileSize = current.files[0].size / 1024 / 1024;
              setFiles({
                fileName: current.files[0].name,
                fileType: current.files[0].type,
                fileSize: current.files[0].size,
                filePreview: URL.createObjectURL(current.files[0]),
              });
            }
            if (props.onChange != undefined) {
              props.onChange(e, current.files);
            }
          }
        }}
        style={{ display: "none" }}
      />
      <div className="max-h-40 w-full bg-gray-50 flex justify-between gap-x-2">
        <div
          className="w-5/12 h-40 bg-gray-100 rounded-lg flex justify-center items-center border-[4px] border-dotted border-gray-300 cursor-pointer"
          onClick={() => {
            if (fileInput.current) {
              (fileInput.current as HTMLInputElement).click();
            }
          }}
        >
          <div className="space-y-1">
            <UploadIcon className="w-10 h-10 text-lakasir-primary mx-auto" />
            <p className="text-sm text-gray-400">{props.label}</p>
          </div>
        </div>
        <div className="w-7/12 py-2 overflow-y-scroll">
          {filesList.map((file, index) => (
            <div className="flex my-2" key={index}>
              <Image
                height={50}
                width={50}
                className="w-10 h-auto text-lakasir-primary mr-5 cursor-pointer"
                src={file.filePreview}
                onClick={(event) => {
                  const target = event.target as HTMLImageElement;
                  const src = target.src;
                  setShowModal(true);
                }}
              />
              <div className="w-full space-y-1">
                <div className="flex w-11/12">
                  <p className="text-sm font-normal">
                    {file.fileName}{" "}
                    <span className="font-light">{file.fileSize}</span>
                  </p>
                  <p className="text-sm font-bold ml-auto cursor-pointer">x</p>
                </div>
                <div className="h-2 w-11/12 rounded-xl bg-blue-200"></div>
              </div>
            </div>
          ))}
          {filesList.length == 0 && ( // if filesList is empty
            <div className="flex justify-center items-center h-full">
              No item
            </div>
          )}
        </div>
      </div>
    </>
  );
};

export { FilePicker };
