import sys
import pickle
import numpy as np
import warnings

warnings.filterwarnings("ignore", category=UserWarning)

if len(sys.argv) < 3:
    print("Usage: python classify.py model_file feature1 feature2 ...")
    sys.exit(1)

model_file = sys.argv[1]
features = np.array(sys.argv[2:], dtype=float).reshape(1, -1)

with open(model_file, 'rb') as f:
    loaded = pickle.load(f)

if isinstance(loaded, dict) and 'model' in loaded:
    model = loaded['model']
    scaler = loaded.get('scaler', None)
elif hasattr(loaded, 'predict'):
    model = loaded
    scaler = None
else:
    print("Unsupported model format.")
    sys.exit(1)

if scaler:
    features = scaler.transform(features)

predicted = model.predict(features)[0]

classification_labels = {
    0: "not_recom",
    1: "priority",
    2: "recommend",
    3: "spec_recommend",
    4: "very-recommend"
}

label = classification_labels.get(predicted, "Unknown")
print(label)  # This is the output returned to PHP
sys.exit(0)