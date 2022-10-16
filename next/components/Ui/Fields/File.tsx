import { UploadIcon } from "@heroicons/react/solid";
import Image from "next/image";
import { ChangeEvent, useEffect, useRef, useState } from "react";
import { Modal } from "../Modals";
import axios from "@/utils/axios";
import toast from "react-hot-toast";
import { TrashIcon } from "@heroicons/react/outline";

interface IFilePickerInterface {
  name: string;
  label: string | JSX.Element;
  accept?: string;
  multiple?: boolean;
  onChange?: (e: ChangeEvent<HTMLInputElement>, file: FileList | null) => void;
  uploadingFiles?: (
    file: File,
    promise: (url: string, formData: FormData) => any,
    setValue: (value: string) => void
  ) => void;
  value?: string[] | string;
}

interface FilesPickerProps {
  fileName: string;
  fileType?: string;
  fileSize?: number;
  filePreview: string;
}

interface IIFilePreviewInterface {
  file: FilesPickerProps;
  index: number;
}

const FilePicker = (props: IFilePickerInterface) => {
  const fileInput = useRef(null);
  const [filesList, setFilesList] = useState<FilesPickerProps[]>([]);
  const [showModal, setShowModal] = useState(false);
  const [previewImage, setPreviewImage] = useState<IIFilePreviewInterface>();
  const [result, setResult] = useState<string>();
  const [results, setResults] = useState<string[]>([]);
  const [loading, setLoading] = useState("0");
  const onUploadProgress = (event: any) => {
    const percentage = Math.round((100 * event.loaded) / event.total);
    setLoading(percentage.toString());
  };
  const deleteListFile = (index: number) => {
    const newFilesList = [...filesList];
    newFilesList.splice(index, 1);
    setFilesList(newFilesList);
    if (props.multiple) {
      const newResults = [...results];
      newResults.splice(index, 1);
      setResults(newResults);
    } else {
      setResult("");
    }
  };

  useEffect(() => {
    if (props.multiple) {
      if (props.value) {
        const newResults = props.value as string[];
        setResults(newResults);
        const newFilesList = newResults.map((result) => {
          return {
            fileName: result.split("/").pop(),
            filePreview: result,
          };
        });
        setFilesList([...newFilesList, ...filesList]);
      }
    } else {
      if (props.value) {
        setResult(props.value as string);
      }
    }
  }, [props.value]);

  return (
    <>
      <Modal open={showModal} onClose={(status) => setShowModal(status)}>
        <div className="w-11/12 mx-auto bg-white pb-7">
          <p className="text-center text-lg py-2 w-full border-2 border-l-none border-t-none border-r-none border-b-2 border-b-gray-300">
            Preview Images
          </p>
          <div className="flex flex-wrap justify-center w-full">
            <img
              src={previewImage?.file.filePreview}
              className="p-1 bg-white border rounded max-w-sm w-full"
            />
          </div>
          <div className="flex flex-wrap justify-center w-full gap-x-3">
            <button
              className="bg-red-500 text-white px-4 py-2 rounded-lg mt-4"
              type="button"
              onClick={() => {
                setShowModal(false);
                if (previewImage != undefined) {
                  deleteListFile(previewImage.index);
                }
              }}
            >
              <div className="flex gap-x-1">
                <TrashIcon className="h-5 w-5 my-auto" /> Delete
              </div>
            </button>
          </div>
        </div>
      </Modal>
      {props.multiple ? (
        results.length > 0 &&
        results.map((res, i) => (
          <input
            key={i}
            type="text"
            name={`${props.name}[${i}]`}
            className="hidden file-inputs"
            value={res}
          />
        ))
      ) : (
        <input
          type="text"
          name={props.name}
          className="hidden"
          value={result}
        />
      )}
      <input
        type="file"
        data-props={JSON.stringify(props)}
        className="hidden file-input"
        accept={props.accept}
        // name={props.name}
        ref={fileInput}
        onChange={(e) => {
          if (fileInput.current) {
            const current = fileInput.current as HTMLInputElement;
            if (current.files != null) {
              // TODO: <29-09-22, sheenazien8>
              // convert size to mb
              // create file preview if the file is not images
              const fileSize = current.files[0].size / 1024 / 1024;
              setFilesList([
                ...filesList,
                {
                  fileName: current.files[0].name,
                  fileType: current.files[0].type,
                  fileSize: current.files[0].size,
                  filePreview: URL.createObjectURL(current.files[0]),
                },
              ]);
              if (props.uploadingFiles != undefined) {
                props.uploadingFiles(
                  current.files[0],
                  (url: string, formData: FormData) => {
                    const headers = {
                      headers: {
                        "Content-Type": "multipart/form-data",
                      },
                      onUploadProgress,
                    };
                    return new Promise((resolve, reject) => {
                      axios
                        .post(url, formData, headers)
                        .then((res) => {
                          setLoading("0");
                          resolve(res.data);
                        })
                        .catch((err) => {
                          reject(err);
                        });
                    });
                  },
                  (value: string) => {
                    if (props.multiple) {
                      setResults([...results, value]);
                    } else {
                      setResult(value);
                    }
                  }
                );
              }
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
            if (loading > "0") {
              toast.error("Please wait until the file is uploaded");
              return;
            }
            if (fileInput.current) {
              (fileInput.current as HTMLInputElement).click();
            }
          }}
        >
          <div className="space-y-1">
            {loading != "0" ? (
              <p className="text-sm text-gray-400">Uploading...</p>
            ) : (
              <>
                <UploadIcon className="w-10 h-10 text-lakasir-primary mx-auto" />
                <p className="text-sm text-gray-400">{props.label}</p>
              </>
            )}
          </div>
        </div>
        <div className="w-7/12 py-2 overflow-y-scroll">
          {props.multiple
            ? filesList.map((file, index) => (
                <div className="flex my-2" key={index}>
                  <img
                    height={50}
                    width={50}
                    className="w-10 h-auto text-lakasir-primary mr-5 cursor-pointer rounded-md"
                    src={file.filePreview}
                    onClick={(event) => {
                      const target = event.target as HTMLImageElement;
                      const src = target.src;
                      setShowModal(true);
                      setPreviewImage({
                        file: file,
                        index: index,
                      });
                    }}
                  />
                  <div className="w-full space-y-2 space-x-1">
                    <div className="flex w-11/12 ml-1">
                      <p className="text-sm font-normal">
                        {file.fileName}{" "}
                        <span className="font-light">{file.fileSize}</span>
                      </p>
                      <p
                        className="text-sm font-bold ml-auto cursor-pointer"
                        onClick={() => {
                          deleteListFile(index);
                        }}
                      >
                        x
                      </p>
                    </div>
                  </div>
                </div>
              ))
            : filesList.length > 0 && (
                <div className="flex my-2">
                  <img
                    height={50}
                    width={50}
                    className="w-10 h-auto text-lakasir-primary mr-5 cursor-pointer rounded-md"
                    src={filesList[filesList.length - 1]?.filePreview}
                    onClick={(event) => {
                      const target = event.target as HTMLImageElement;
                      const src = target.src;
                      setShowModal(true);
                      setPreviewImage({
                        file: filesList[filesList.length - 1],
                        index: 0,
                      });
                    }}
                  />
                  <div className="w-full space-y-2 space-x-1">
                    <div className="flex w-11/12 ml-1">
                      <p className="text-sm font-normal">
                        {filesList[filesList.length - 1]?.fileName}{" "}
                        <span className="font-light">
                          {filesList[filesList.length - 1]?.fileSize}
                        </span>
                      </p>
                      <p
                        className="text-sm font-bold ml-auto cursor-pointer"
                        onClick={() => {
                          deleteListFile(0);
                        }}
                      >
                        x
                      </p>
                    </div>
                  </div>
                </div>
              )}
          {filesList.length == 0 && (
            <div className="flex justify-center items-center h-full text-gray-600">
              No File Uploaded
            </div>
          )}
        </div>
      </div>
    </>
  );
};

export { FilePicker };
