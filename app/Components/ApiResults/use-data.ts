import {useEffect, useState} from "react";
import {DataInterface} from "./data-interface";

export const useData = (url: string, initialData: DataInterface | null = null): DataInterface | null => {
  const [data, setData] = useState<DataInterface | null>(initialData);
  console.log(data, url);
  const reload = async () => {
    const response = await fetch(url);
    setData(await response.json())
  };

  useEffect(() => {
    if (!data) {
      reload().then();
    }
  }, [url]); // do not add data to dependencies

  useEffect(() => {
    if (data && new Date(data.times.gameEnd).getTime() + 1000 < new Date().getTime()) {
      return;
    }
    if (data && data.refreshDelay) {
      setTimeout(reload, Math.max(data.refreshDelay, 5000));
    }
  }, [data]);

  return data;
}
