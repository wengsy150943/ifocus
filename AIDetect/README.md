## AIDetect

### FaceDetect

 **a. face.py**

* This script works for video files by setting the video source parameter video `--source` (path to a video file)(0 for camera) .
* return **True/False**

eg:

```python
# load and run detection on video at path "videos/chess.mov"
  python detect_single_threaded.py --source videos/chess.mov
```

**b. haarcascade_frontalface_alt.xml**

* Face Classifier

### HandDetect

#### **Guide**

##### step 1

* Load the `frozen_inference_graph.pb` trained on the hands dataset as well as the corresponding label map. In this repo, this is done in the `utils/detector_utils.py` script by the `load_inference_graph` method.

```python
detection_graph = tf.Graph()
  with detection_graph.as_default():
      od_graph_def = tf.GraphDef()
      with tf.gfile.GFile(PATH_TO_CKPT, 'rb') as fid:
          serialized_graph = fid.read()
          od_graph_def.ParseFromString(serialized_graph)
          tf.import_graph_def(od_graph_def, name='')
      sess = tf.Session(graph=detection_graph)
  print(">  ====== Hand Inference graph loaded.")
```

##### step 2

- Detect hands. In this repo, this is done in the `utils/detector_utils.py` script by the `detect_objects` method.

  ```python
  (boxes, scores, classes, num) = sess.run(
        [detection_boxes, detection_scores,
            detection_classes, num_detections],
        feed_dict={image_tensor: image_np_expanded})
  ```

##### step 3

- Visualize detected bounding detection_boxes. In this repo, this is done in the `utils/detector_utils.py` script by the `draw_box_on_image` method.

**detect_single_threaded.py**

* This script works for video files by setting the video source parameter video `--source` (path to a video file)(0 for camera) .
* return **True/False** 

eg:

```python
# load and run detection on video at path "videos/chess.mov"
  python detect_single_threaded.py --source videos/chess.mov
```

